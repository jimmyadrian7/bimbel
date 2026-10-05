(() => {
    "use strict";

    angular.module('app.module.konfigurasi.template_kwitansi')
        .controller('TemplateKwitansiController', TemplateKwitansiController);

    TemplateKwitansiController.$inject = ['$scope', '$window', '$timeout', '$http', '$sce', 'req', 'logger', 'pdfImage'];

    /**
     * Konfigurasi > Template Kwitansi, laid out like a report editor: the receipt itself is the
     * canvas (click a block to select it, drag it to move it, drag the divider to resize), a
     * properties panel on the right edits the selection, and "Edit kode" opens the Twig text of
     * a section. Everything is a working copy ({layout, sections}) with undo/redo; nothing is
     * saved until "Simpan".
     */
    function TemplateKwitansiController($scope, $window, $timeout, $http, $sce, req, logger, pdfImage)
    {
        let vm = this;
        const URL = 'report/template/kwitansi';
        const BASE = $window.location.pathname.split('/admin')[0] + '/api';
        const CHANGE_DELAY = 250;     // wait for the person to stop typing/sliding before rendering
        const HISTORY_LIMIT = 100;
        const SELECTIONS = ['page', 'header', 'program', 'terima', 'nominal', 'rekening', 'keterangan'];

        let history = [];
        let cursor = -1;
        let savedSnapshot = '';
        let changeTimer = null;
        let canvasSeq = 0;
        let canvasLatest = null;
        let pdfSeq = 0;
        let pdfLatest = null;
        let pdfObjectUrl = null;

        vm.loaded = false;
        vm.installed = true;
        vm.view = 'canvas';            // 'canvas' (edit) | 'pdf' (the real print output)
        vm.selection = 'page';
        vm.zoom = null;                // null = fit the width
        vm.scale = 1;
        vm.layout = null;
        vm.layoutStandar = null;
        vm.blocks = {};
        vm.limits = {};
        vm.sections = [];
        vm.examples = {};
        vm.variables = [];
        vm.allowed = {};
        vm.programCount = 0;
        vm.dirty = false;
        vm.saving = false;
        vm.saveError = null;
        vm.sample = { status: 'l', tagihanId: '', open: false };
        vm.canvas = { html: null, loading: false, error: null, errors: {}, pages: null, overflowX: false, stale: false };
        vm.pdf = { url: null, data: null, loading: false, error: null, pages: null, stale: true, exporting: false };
        vm.code = null;

        vm.touch = touch;
        vm.undo = undo;
        vm.redo = redo;
        vm.canUndo = canUndo;
        vm.canRedo = canRedo;
        vm.setView = setView;
        vm.setZoom = setZoom;
        vm.onCanvasEvent = onCanvasEvent;
        vm.select = select;
        vm.selectionLabel = selectionLabel;
        vm.moveBlockTo = moveBlockTo;
        vm.shift = shift;
        vm.isFirst = isFirst;
        vm.isLast = isLast;
        vm.isCustom = isCustom;
        vm.sectionOf = findSection;
        vm.programCustom = programCustom;
        vm.isLayoutCustom = isLayoutCustom;
        vm.resetLayout = resetLayout;
        vm.resetSection = resetSection;
        vm.fitPrograms = fitPrograms;
        vm.errorSections = errorSections;
        vm.pageInfo = pageInfo;
        vm.openCode = openCode;
        vm.applyCode = applyCode;
        vm.cancelCode = cancelCode;
        vm.draftDefault = draftDefault;
        vm.draftExample = draftExample;
        vm.insertVariable = insertVariable;
        vm.hasUnsaved = hasUnsaved;
        vm.discardAll = discardAll;
        vm.refreshPdf = refreshPdf;
        vm.exportImage = exportImage;
        vm.save = save;

        activate();

        function activate()
        {
            req.get(URL).then(state => {
                applyState(state);
                vm.loaded = true;

                if (vm.installed)
                {
                    renderCanvas();
                }
            }).catch(angular.noop);
        }

        function applyState(state)
        {
            vm.installed = state.terpasang;
            vm.examples = state.contoh || {};
            vm.variables = state.variabel || [];
            vm.allowed = state.diizinkan || {};
            vm.programCount = state.jumlah_program;
            vm.layout = angular.copy(state.layout);
            vm.layoutStandar = angular.copy(state.layout_standar);
            vm.limits = state.batas || {};
            vm.blocks = {};
            (state.blok || []).forEach(b => vm.blocks[b.key] = b.label);
            vm.sections = state.sections.map(s => ({
                key: s.key,
                label: s.label,
                keterangan: s.keterangan,
                default: s.default,
                konten: s.konten,
                error: null
            }));

            // The state just loaded/saved is the baseline for "unsaved" and for undo.
            savedSnapshot = snapshot();
            history = [savedSnapshot];
            cursor = 0;
            vm.dirty = false;
        }

        // ------------------------------------------------------------------
        // Working copy, history
        // ------------------------------------------------------------------

        // Newlines/trailing whitespace are not a "change" (the server compares the same way).
        function normalize(text)
        {
            return (text || '').replace(/\r\n?/g, '\n').replace(/\s+$/, '');
        }

        function snapshot()
        {
            let sections = {};
            vm.sections.forEach(s => sections[s.key] = normalize(s.konten));

            return JSON.stringify({ layout: vm.layout, sections: sections });
        }

        function restore(snap)
        {
            let data = JSON.parse(snap);

            vm.layout = data.layout;
            vm.sections.forEach(s => {
                s.konten = data.sections[s.key];
                s.error = null;
            });
        }

        function commit()
        {
            let snap = snapshot();

            if (snap !== history[cursor])
            {
                history.splice(cursor + 1);
                history.push(snap);
                cursor++;

                if (history.length > HISTORY_LIMIT)
                {
                    history.shift();
                    cursor--;
                }
            }

            vm.dirty = snap !== savedSnapshot;
        }

        // Called after every edit. Typing/sliding is coalesced; discrete actions pass `immediately`.
        function touch(immediately)
        {
            vm.pdf.stale = true;
            if (vm.view === 'pdf')
            {
                vm.canvas.stale = true;
            }

            $timeout.cancel(changeTimer);
            changeTimer = null;

            if (immediately)
            {
                commit();
                renderNow();
                return;
            }

            changeTimer = $timeout(() => {
                changeTimer = null;
                commit();
                renderNow();
            }, CHANGE_DELAY);
        }

        // Make a pending (debounced) edit part of the history before doing something else.
        function flush()
        {
            if (changeTimer)
            {
                $timeout.cancel(changeTimer);
                changeTimer = null;
                commit();
            }
        }

        function canUndo()
        {
            return cursor > 0 || !!changeTimer;
        }

        function canRedo()
        {
            return cursor < history.length - 1 && !changeTimer;
        }

        function undo()
        {
            flush();

            if (cursor > 0)
            {
                cursor--;
                restore(history[cursor]);
                vm.dirty = history[cursor] !== savedSnapshot;
                touchView();
            }
        }

        function redo()
        {
            if (cursor < history.length - 1)
            {
                cursor++;
                restore(history[cursor]);
                vm.dirty = history[cursor] !== savedSnapshot;
                touchView();
            }
        }

        // After undo/redo/discard: history already has the state, only the view has to follow.
        function touchView()
        {
            vm.pdf.stale = true;
            if (vm.view === 'pdf')
            {
                vm.canvas.stale = true;
            }
            renderNow();
        }

        function hasUnsaved()
        {
            return vm.dirty;
        }

        function discardAll()
        {
            if (!$window.confirm('Batalkan semua perubahan yang belum disimpan?'))
            {
                return;
            }

            $timeout.cancel(changeTimer);
            changeTimer = null;
            restore(savedSnapshot);
            history = [savedSnapshot];
            cursor = 0;
            vm.dirty = false;
            vm.saveError = null;
            touchView();
        }

        // ------------------------------------------------------------------
        // Selection and the layout actions behind the panel and the canvas
        // ------------------------------------------------------------------

        function select(key)
        {
            vm.selection = SELECTIONS.indexOf(key) > -1 ? key : 'page';
        }

        function selectionLabel()
        {
            let key = vm.selection;

            if (key === 'page') return 'Halaman';
            if (vm.blocks[key]) return vm.blocks[key];

            let section = findSection(key);

            return section ? section.label : '';
        }

        function moveBlockTo(key, toIndex)
        {
            let order = vm.layout.urutan;
            let from = order.indexOf(key);

            if (from < 0 || toIndex < 0 || toIndex >= order.length || toIndex === from)
            {
                return;
            }

            order.splice(toIndex, 0, order.splice(from, 1)[0]);
            touch(true);
        }

        function shift(key, delta)
        {
            moveBlockTo(key, vm.layout.urutan.indexOf(key) + delta);
        }

        function isFirst(key)
        {
            return vm.layout.urutan.indexOf(key) === 0;
        }

        function isLast(key)
        {
            return vm.layout.urutan.indexOf(key) === vm.layout.urutan.length - 1;
        }

        function findSection(key)
        {
            return vm.sections.find(s => s.key === key) || null;
        }

        function isCustom(key)
        {
            let section = findSection(key);

            return !!section && normalize(section.konten) !== normalize(section.default);
        }

        // Hand-written program code wins over the "program in several rows" setting.
        function programCustom()
        {
            return isCustom('program');
        }

        function isLayoutCustom()
        {
            return !angular.equals(vm.layout, vm.layoutStandar);
        }

        function resetLayout()
        {
            if (isLayoutCustom() && !$window.confirm('Kembalikan tata letak (urutan, lebar, margin, susunan program) ke standar?'))
            {
                return;
            }

            vm.layout = angular.copy(vm.layoutStandar);
            touch(true);
        }

        function resetSection(key)
        {
            let section = findSection(key);

            if (!section || !isCustom(key))
            {
                return;
            }

            if (!$window.confirm(`Kembalikan bagian "${section.label}" ke template standar?`))
            {
                return;
            }

            section.konten = section.default;
            section.error = null;
            touch(true);
        }

        // The usual cure when programs are added: one row per N programs instead of one long row.
        function fitPrograms()
        {
            vm.layout.program.susun = true;
            vm.selection = 'program';
            touch(true);
        }

        function setZoom(zoom)
        {
            vm.zoom = zoom;
        }

        // Returns the same object while nothing changed (a template expression must be stable for Angular's digest).
        const EXACT = { pages: 0, exact: true };
        const GUESS = { pages: 0, exact: false };

        function pageInfo()
        {
            if (vm.pdf.pages && !vm.pdf.stale)
            {
                EXACT.pages = vm.pdf.pages;
                return EXACT;
            }

            if (vm.view === 'canvas' && vm.canvas.pages)
            {
                GUESS.pages = vm.canvas.pages;
                return GUESS;
            }

            return null;
        }

        function errorSections()
        {
            return vm.sections.filter(s => !!s.error);
        }

        // ------------------------------------------------------------------
        // Events from the canvas (see kwitansiCanvas)
        // ------------------------------------------------------------------

        function onCanvasEvent(msg)
        {
            switch (msg.kw)
            {
                case 'select':
                    select(msg.key);
                    break;

                case 'move':
                    if (vm.blocks[msg.key] && angular.isNumber(msg.toIndex))
                    {
                        vm.selection = msg.key;
                        moveBlockTo(msg.key, msg.toIndex);
                    }
                    break;

                case 'resize':
                    if (angular.isNumber(msg.lebar_kiri) && vm.limits.lebar_kiri)
                    {
                        vm.layout.lebar_kiri = Math.max(vm.limits.lebar_kiri[0], Math.min(vm.limits.lebar_kiri[1], Math.round(msg.lebar_kiri)));
                        vm.selection = 'keterangan';
                        touch(true);
                    }
                    break;

                case 'size':
                    vm.canvas.pages = msg.pages;
                    vm.canvas.overflowX = !!msg.overflowX;
                    break;

                case 'scale':
                    vm.scale = msg.scale;
                    break;
            }
        }

        // ------------------------------------------------------------------
        // Rendering: the canvas (HTML) and the real PDF
        // ------------------------------------------------------------------

        // Background calls do not use `req` (it shows a full-page loader on every call, which would
        // flash on every drag). Errors are shown in the editor instead of as toasts.
        function post(path, body)
        {
            return $http.post(`${BASE}/${path}`, body).then(response => response.data, error => {
                let message = 'Tidak dapat terhubung ke server. Periksa koneksi anda.';

                if (error && error.status === 501)
                {
                    message = 'Sesi anda berakhir. Silakan login kembali.';
                }
                else if (error && error.data && error.data.exception && error.data.exception.message)
                {
                    message = error.data.exception.message;
                }
                else if (error && error.status >= 500)
                {
                    message = 'Terjadi kesalahan di server.';
                }

                return { error: message, errors: {}, transport: true };
            });
        }

        function requestBody()
        {
            let sections = {};
            vm.sections.forEach(s => sections[s.key] = s.konten);

            return {
                sections: sections,
                layout: vm.layout,
                status: vm.sample.status,
                tagihan_id: parseInt(vm.sample.tagihanId, 10) || null
            };
        }

        function setSectionErrors(errors)
        {
            vm.sections.forEach(s => s.error = (errors && errors[s.key]) || null);
        }

        function renderNow()
        {
            return vm.view === 'pdf' ? renderPdf() : renderCanvas();
        }

        function renderCanvas()
        {
            if (!vm.installed)
            {
                return $window.Promise.resolve({ ok: false, errors: {} });
            }

            let mine = ++canvasSeq;
            vm.canvas.loading = true;

            canvasLatest = post(`${URL}/html`, requestBody()).then(result => {
                if (mine !== canvasSeq)
                {
                    return canvasLatest; // a newer render superseded this one: its result is the answer
                }

                vm.canvas.loading = false;
                vm.canvas.stale = false;
                vm.canvas.errors = result.errors || {};
                setSectionErrors(vm.canvas.errors);

                if (result.error)
                {
                    vm.canvas.error = result.error;
                    return { ok: false, error: result.error, errors: vm.canvas.errors };
                }

                vm.canvas.error = null;
                vm.canvas.html = result.html;
                return { ok: true };
            });

            return canvasLatest;
        }

        function renderPdf()
        {
            let mine = ++pdfSeq;
            vm.pdf.loading = true;

            pdfLatest = post(`${URL}/preview`, requestBody()).then(result => {
                if (mine !== pdfSeq)
                {
                    return pdfLatest;
                }

                vm.pdf.loading = false;
                setSectionErrors(result.errors);

                if (result.error)
                {
                    vm.pdf.error = result.error;
                    return { ok: false, error: result.error, errors: result.errors || {} };
                }

                vm.pdf.error = null;
                vm.pdf.pages = result.pages;
                vm.pdf.data = result.data;
                vm.pdf.stale = false;
                showPdf(result.data);
                return { ok: true };
            });

            return pdfLatest;
        }

        function refreshPdf()
        {
            vm.pdf.stale = true;
            return renderPdf();
        }

        // The PDF being shown, saved as a picture (exactly what it prints).
        function exportImage(format)
        {
            if (!vm.pdf.data || vm.pdf.stale || vm.pdf.exporting)
            {
                return;
            }

            vm.pdf.exporting = true;

            return pdfImage.download(vm.pdf.data, 'kwitansi-contoh', { format: format })
                .catch(angular.noop)
                .finally(() => { vm.pdf.exporting = false; });
        }

        function setView(view)
        {
            flush();
            vm.view = view;

            if (view === 'pdf' && (vm.pdf.stale || !vm.pdf.url))
            {
                renderPdf();
            }
            else if (view === 'canvas' && (vm.canvas.stale || !vm.canvas.html))
            {
                renderCanvas();
            }
        }

        function showPdf(base64)
        {
            let binary = $window.atob(base64);
            let bytes = new Uint8Array(binary.length);
            for (let i = 0; i < binary.length; i++)
            {
                bytes[i] = binary.charCodeAt(i);
            }

            revokePdf();
            pdfObjectUrl = $window.URL.createObjectURL(new $window.Blob([bytes], { type: 'application/pdf' }));
            vm.pdf.url = $sce.trustAsResourceUrl(pdfObjectUrl + '#toolbar=0&navpanes=0&view=Fit');
        }

        function revokePdf()
        {
            if (pdfObjectUrl)
            {
                $window.URL.revokeObjectURL(pdfObjectUrl);
                pdfObjectUrl = null;
            }
        }

        // ------------------------------------------------------------------
        // "Edit kode": the Twig text of one section, in an overlay
        // ------------------------------------------------------------------

        function openCode(key)
        {
            let section = findSection(key);

            if (!section)
            {
                return;
            }

            vm.code = {
                key: key,
                label: section.label,
                keterangan: section.keterangan,
                draft: section.konten,
                error: section.error,
                applying: false,
                help: false
            };

            $timeout(() => {
                let textarea = $window.document.getElementById('kwitansi-code-draft');
                if (textarea)
                {
                    textarea.focus();
                }
            }, 50);
        }

        function applyCode()
        {
            let code = vm.code;
            let section = code && findSection(code.key);

            if (!section || code.applying)
            {
                return;
            }

            if (normalize(code.draft) === normalize(section.konten))
            {
                vm.code = null;
                return;
            }

            flush();

            // Try the new text; keep it only if the server renders it. The working copy therefore
            // never holds a template that does not work (so "Simpan" can't be blocked by it).
            let previous = section.konten;
            let before = { error: vm.canvas.error, errors: vm.canvas.errors };

            code.applying = true;
            code.error = null;
            section.konten = code.draft;
            vm.pdf.stale = true;
            if (vm.view === 'pdf')
            {
                vm.canvas.stale = true;
            }

            return renderNow().then(result => {
                code.applying = false;

                if (result.ok)
                {
                    commit();
                    vm.code = null;
                    return;
                }

                section.konten = previous;
                vm.canvas.error = before.error;
                vm.canvas.errors = before.errors;
                setSectionErrors(before.errors);
                code.error = (result.errors && result.errors[code.key]) || result.error;
            });
        }

        function cancelCode()
        {
            if (vm.code && !vm.code.applying)
            {
                let section = findSection(vm.code.key);

                if (section && normalize(vm.code.draft) !== normalize(section.konten) &&
                    !$window.confirm('Buang perubahan kode yang belum diterapkan?'))
                {
                    return;
                }

                vm.code = null;
            }
        }

        function draftDefault()
        {
            let section = vm.code && findSection(vm.code.key);

            if (section)
            {
                vm.code.draft = section.default;
                vm.code.error = null;
            }
        }

        function draftExample(example)
        {
            if (vm.code && (normalize(vm.code.draft) === normalize((findSection(vm.code.key) || {}).default) ||
                $window.confirm('Ganti isi kode dengan contoh ini?')))
            {
                vm.code.draft = example.konten;
                vm.code.error = null;
            }
        }

        // Inserts a variable snippet into the code draft, at the cursor.
        function insertVariable(variable)
        {
            let textarea = $window.document.getElementById('kwitansi-code-draft');

            if (!vm.code || !textarea)
            {
                return;
            }

            let text = vm.code.draft || '';
            let start = typeof textarea.selectionStart === 'number' ? textarea.selectionStart : text.length;
            let end = typeof textarea.selectionEnd === 'number' ? textarea.selectionEnd : start;

            vm.code.draft = text.slice(0, start) + variable.sisip + text.slice(end);

            let caret = start + variable.sisip.length;
            $timeout(() => {
                textarea.focus();
                textarea.setSelectionRange(caret, caret);
            });
        }

        // ------------------------------------------------------------------
        // Save and leaving the page
        // ------------------------------------------------------------------

        function save()
        {
            flush();
            vm.saving = true;
            vm.saveError = null;

            let sections = {};
            vm.sections.forEach(s => sections[s.key] = s.konten);

            return req.post(`${URL}/save`, { sections: sections, layout: vm.layout }).then(() => {
                // The server stores what was sent (a part equal to the default is simply not stored),
                // so the working copy is now the saved one; undo history stays.
                savedSnapshot = snapshot();
                vm.dirty = false;
                logger.success('Template kwitansi disimpan');
            }).catch(message => {
                // The server rejects invalid templates; keep the work and show why.
                vm.saveError = message;
            }).finally(() => {
                vm.saving = false;
            });
        }

        function onKeyDown(event)
        {
            if (vm.code)
            {
                if (event.key === 'Escape')
                {
                    $scope.$apply(cancelCode);
                }
                return;
            }

            let target = event.target || {};
            let typing = /^(INPUT|TEXTAREA|SELECT)$/.test(target.tagName) || target.isContentEditable;
            let ctrl = event.ctrlKey || event.metaKey;

            if (typing || !ctrl || vm.view !== 'canvas' && vm.view !== 'pdf')
            {
                return;
            }

            let key = (event.key || '').toLowerCase();

            if (key === 'z' && !event.shiftKey)
            {
                event.preventDefault();
                $scope.$apply(undo);
            }
            else if (key === 'y' || (key === 'z' && event.shiftKey))
            {
                event.preventDefault();
                $scope.$apply(redo);
            }
        }

        function onBeforeUnload(event)
        {
            if (vm.dirty)
            {
                event.preventDefault();
                event.returnValue = '';
            }
        }

        $window.addEventListener('keydown', onKeyDown);
        $window.addEventListener('beforeunload', onBeforeUnload);

        // Don't lose a long editing session by clicking a menu item.
        $scope.$on('$stateChangeStart', event => {
            if (vm.dirty && !$window.confirm('Ada perubahan template yang belum disimpan. Tinggalkan halaman ini?'))
            {
                event.preventDefault();
            }
        });

        $scope.$on('$destroy', () => {
            $window.removeEventListener('keydown', onKeyDown);
            $window.removeEventListener('beforeunload', onBeforeUnload);
            $timeout.cancel(changeTimer);
            revokePdf();
        });
    }
})()
