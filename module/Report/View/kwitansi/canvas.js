/*
 * Template Kwitansi - canvas script.
 *
 * This file is TRUSTED code that KwitansiTemplate::canvasDocument() puts (with a CSP nonce)
 * into the sandboxed iframe that shows the kwitansi in Konfigurasi > Template Kwitansi.
 * It lets the person select blocks, drag them to reorder and drag the column divider, and
 * reports what happened to the parent page with postMessage. It never changes the saved
 * template itself: the parent updates its layout and asks the server for a new render.
 *
 * `KW` (labels, draggable block keys, limits, page size) is prepended by the server.
 * Messages out: ready/size {w,h,pages,overflowX}, select {key}, move {key,toIndex}, resize {lebar_kiri}
 * Messages in:  select-ui {key}
 */
(function () {
    'use strict';

    var DRAG_PX = 4;
    var ui, hoverBox, hoverChip, selBox, selChip, dropLine, ghost, divider, badge, edge;
    var breaks = [];
    var selected = null;
    var press = null;      // pointer is down on a block: {key, x, y, dragging, ...}
    var sizing = null;     // pointer is down on the column divider
    var lastSize = '';

    function el(cls, parent) {
        var node = document.createElement('div');
        node.className = cls;
        (parent || ui).appendChild(node);
        return node;
    }

    function post(message) {
        message.kw = message.kw || '';
        try { parent.postMessage(message, '*'); } catch (e) { /* no parent */ }
    }

    function block(node) {
        while (node && node.nodeType === 1) {
            if (node.hasAttribute('data-kw-block')) { return node; }
            node = node.parentNode;
        }
        return null;
    }

    function blocks() {
        return Array.prototype.slice.call(document.querySelectorAll('[data-kw-block]'));
    }

    function byKey(key) {
        return document.querySelector('[data-kw-block="' + key + '"]');
    }

    function isDraggable(key) {
        return KW.draggable.indexOf(key) > -1;
    }

    function rect(node) {
        var r = node.getBoundingClientRect();
        return { x: r.left + window.pageXOffset, y: r.top + window.pageYOffset, w: r.width, h: r.height };
    }

    function place(box, r, pad) {
        pad = pad || 0;
        box.style.left = (r.x - pad) + 'px';
        box.style.top = (r.y - pad) + 'px';
        box.style.width = (r.w + pad * 2) + 'px';
        box.style.height = (r.h + pad * 2) + 'px';
        box.style.display = 'block';
    }

    function label(key) {
        return KW.labels[key] || key;
    }

    // ---- boxes ---------------------------------------------------------

    function showHover(node) {
        if (!node || press || sizing) { hoverBox.style.display = 'none'; return; }
        var key = node.getAttribute('data-kw-block');
        if (key === selected) { hoverBox.style.display = 'none'; return; }
        place(hoverBox, rect(node), 1);
        hoverChip.textContent = label(key);
    }

    function showSelection() {
        var node = selected ? byKey(selected) : null;
        if (!node) { selBox.style.display = 'none'; return; }
        place(selBox, rect(node), 1);
        selChip.textContent = label(selected);
        hoverBox.style.display = 'none';
    }

    function placeDivider() {
        var table = document.querySelector('[data-kw-part="tabel"]');
        var left = document.querySelector('[data-kw-part="kiri"]');
        var right = byKey('keterangan');

        if (!table || !left || !right) { divider.style.display = 'none'; return; }

        var a = rect(left), b = rect(right), t = rect(table);
        var x = (a.x + a.w + b.x) / 2;

        divider.style.left = (x - 7) + 'px';
        divider.style.top = t.y + 'px';
        divider.style.height = t.h + 'px';
        divider.style.display = 'block';
    }

    function guides() {
        var pageH = KW.page.h;

        // The extent of the receipt itself: measured on body (documentElement would also count the
        // iframe's own size) and with our overlays out of the way (they would count as content).
        ui.style.display = 'none';
        var total = Math.max(document.body.scrollHeight, document.body.offsetHeight);
        var scrollW = Math.max(document.body.scrollWidth, document.body.offsetWidth);
        ui.style.display = '';

        var pages = Math.max(1, Math.ceil((total - 1) / pageH));
        var overflowX = scrollW > KW.page.w + 1;

        while (breaks.length < pages - 1) { breaks.push(el('kw-break')); }
        breaks.forEach(function (line, i) {
            if (i < pages - 1) {
                line.style.top = ((i + 1) * pageH) + 'px';
                line.setAttribute('data-label', 'Halaman ' + (i + 2) + ' (terpotong saat dicetak)');
                line.style.display = 'block';
            } else {
                line.style.display = 'none';
            }
        });

        edge.style.display = overflowX ? 'block' : 'none';
        edge.style.left = KW.page.w + 'px';
        edge.style.height = total + 'px';

        return { pages: pages, overflowX: overflowX, w: Math.max(scrollW, KW.page.w), h: Math.max(total, pageH) };
    }

    function refresh() {
        var size = guides();
        showSelection();
        if (!sizing) { placeDivider(); }

        var key = size.w + 'x' + size.h + 'x' + size.pages;
        if (key !== lastSize) {
            lastSize = key;
            post({ kw: 'size', w: size.w, h: size.h, pages: size.pages, overflowX: size.overflowX });
        }
    }

    // ---- reorder -------------------------------------------------------

    // Where the dragged block would land if dropped at pageY: {toIndex, y (indicator line), x, w}
    function dropTarget(key, pointerY) {
        var others = blocks().filter(function (node) {
            var k = node.getAttribute('data-kw-block');
            return k !== key && isDraggable(k);
        });
        var index = 0;

        others.forEach(function (node) {
            var r = rect(node);
            if (pointerY > r.y + r.h / 2) { index++; }
        });

        var first = rect(others[0]);
        var y = first.y;
        if (index > 0) {
            var prev = rect(others[index - 1]);
            y = prev.y + prev.h;
        }

        return { toIndex: index, y: y, x: first.x, w: first.w };
    }

    function startDrag() {
        press.dragging = true;
        press.origin = rect(byKey(press.key));
        ghost.style.display = 'block';
        document.documentElement.classList.add('kw-dragging');
        hoverBox.style.display = 'none';
        try { document.body.setPointerCapture(press.id); } catch (e) { /* ignore */ }
    }

    function moveDrag(e) {
        var dy = e.clientY - press.y;
        var o = press.origin;
        var target = dropTarget(press.key, e.clientY + window.pageYOffset);

        ghost.style.left = o.x + 'px';
        ghost.style.top = (o.y + dy) + 'px';
        ghost.style.width = o.w + 'px';
        ghost.style.height = o.h + 'px';

        dropLine.style.left = target.x + 'px';
        dropLine.style.top = (target.y - 2) + 'px';
        dropLine.style.width = target.w + 'px';
        dropLine.style.display = 'block';

        press.toIndex = target.toIndex;
    }

    function endDrag(commit) {
        var press0 = press;
        press = null;
        ghost.style.display = 'none';
        dropLine.style.display = 'none';
        document.documentElement.classList.remove('kw-dragging');
        try { document.body.releasePointerCapture(press0.id); } catch (e) { /* ignore */ }

        if (!commit || !press0.dragging) { return false; }

        var order = blocks().map(function (n) { return n.getAttribute('data-kw-block'); }).filter(isDraggable);
        var from = order.indexOf(press0.key);

        if (press0.toIndex !== undefined && press0.toIndex !== from) {
            post({ kw: 'move', key: press0.key, toIndex: press0.toIndex });
        }
        return true;
    }

    // ---- resize --------------------------------------------------------

    function startSizing(e) {
        var left = document.querySelector('[data-kw-part="kiri"]');
        var right = byKey('keterangan');
        var table = document.querySelector('[data-kw-part="tabel"]');
        if (!left || !right || !table) { return; }

        var start = parseFloat(left.getAttribute('width')) || 80;
        sizing = { x: e.clientX, id: e.pointerId, start: start, pct: start, left: left, right: right, width: rect(table).w,
                   leftAttr: left.getAttribute('width'), rightAttr: right.getAttribute('width') };
        document.documentElement.classList.add('kw-sizing');
        hoverBox.style.display = 'none';
        try { document.body.setPointerCapture(e.pointerId); } catch (err) { /* ignore */ }
        e.preventDefault();
    }

    function moveSizing(e) {
        var lim = KW.limits.lebar_kiri;
        var pct = Math.round(sizing.start + (e.clientX - sizing.x) / sizing.width * 100);
        pct = Math.max(lim[0], Math.min(lim[1], pct));

        sizing.pct = pct;
        sizing.left.setAttribute('width', pct + '%');
        sizing.right.setAttribute('width', (100 - pct) + '%');
        placeDivider();
        showSelection();

        badge.textContent = pct + '% | ' + (100 - pct) + '%';
        badge.style.left = (parseFloat(divider.style.left) + 7) + 'px';
        badge.style.top = (parseFloat(divider.style.top) - 28) + 'px';
        badge.style.display = 'block';
    }

    function endSizing(commit) {
        var s = sizing;
        sizing = null;
        badge.style.display = 'none';
        document.documentElement.classList.remove('kw-sizing');
        try { document.body.releasePointerCapture(s.id); } catch (e) { /* ignore */ }

        if (commit && s.pct !== s.start) {
            post({ kw: 'resize', lebar_kiri: s.pct });
        } else {
            s.left.setAttribute('width', s.leftAttr);
            s.right.setAttribute('width', s.rightAttr);
        }
        refresh();
    }

    // ---- events --------------------------------------------------------

    function onDown(e) {
        if (e.button !== undefined && e.button !== 0) { return; }

        if (e.target === divider) { startSizing(e); return; }

        var node = block(e.target);
        press = { id: e.pointerId, x: e.clientX, y: e.clientY, key: node ? node.getAttribute('data-kw-block') : 'page', dragging: false };
    }

    function onMove(e) {
        if (sizing) { moveSizing(e); return; }

        if (press) {
            if (!press.dragging && isDraggable(press.key) &&
                Math.abs(e.clientX - press.x) + Math.abs(e.clientY - press.y) > DRAG_PX) {
                startDrag();
            }
            if (press.dragging) { moveDrag(e); }
            return;
        }

        showHover(block(e.target));
    }

    function onUp(e) {
        if (sizing) { endSizing(true); return; }
        if (!press) { return; }

        var key = press.key;
        var dragged = endDrag(true);

        if (!dragged) {
            selected = key;
            showSelection();
            post({ kw: 'select', key: key });
        }
    }

    function onCancel() {
        if (sizing) { endSizing(false); }
        if (press) { endDrag(false); }
    }

    function init() {
        var style = document.createElement('style');
        style.textContent = KW.css;
        document.head.appendChild(style);

        ui = el('kw-ui', document.body);
        hoverBox = el('kw-box kw-hover');
        hoverChip = el('kw-chip', hoverBox);
        selBox = el('kw-box kw-selected');
        selChip = el('kw-chip', selBox);
        ghost = el('kw-ghost');
        dropLine = el('kw-drop');
        divider = el('kw-divider');
        badge = el('kw-badge');
        edge = el('kw-edge');
        edge.setAttribute('data-label', 'Batas halaman: isi di luar garis ini terpotong');

        document.addEventListener('pointerdown', onDown);
        document.addEventListener('pointermove', onMove);
        document.addEventListener('pointerup', onUp);
        document.addEventListener('pointercancel', onCancel);
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { onCancel(); } });
        document.addEventListener('pointerleave', function () { if (!press && !sizing) { hoverBox.style.display = 'none'; } });
        document.documentElement.addEventListener('mouseleave', function () { if (!press && !sizing) { hoverBox.style.display = 'none'; } });

        window.addEventListener('message', function (e) {
            if (e.source !== parent || !e.data || e.data.kw !== 'select-ui') { return; }
            selected = typeof e.data.key === 'string' && e.data.key !== 'page' ? e.data.key : null;
            showSelection();
        });

        if (window.ResizeObserver) { new ResizeObserver(refresh).observe(document.body); }
        window.addEventListener('load', refresh);

        refresh();
        post({ kw: 'ready' });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
