(() => {
    "use strict";

    angular.module('app.module.konfigurasi.template_kwitansi')
        .directive('kwitansiCanvas', kwitansiCanvas)
        .directive('kwitansiDragText', kwitansiDragText);

    kwitansiCanvas.$inject = ['$window', '$timeout'];

    /**
     * The editable page: <kwitansi-canvas html="..." selected="..." zoom="..." on-event="vm.onCanvasEvent(msg)">.
     *
     * `html` is the document built by the server (KwitansiTemplate::canvasDocument()). It contains
     * admin-written markup, so it is shown in an iframe that is sandboxed WITHOUT allow-same-origin
     * (an opaque origin: it can run only our nonce'd editor script, cannot read this page, its
     * cookies or call the API). The only channel is postMessage, and messages are accepted only
     * when event.source is one of our own two iframes.
     *
     * Two iframes take turns: a new document loads in the hidden one and is swapped in when its
     * script says `ready`, so re-rendering after every change does not flash white.
     *
     * Messages from the page: {kw: 'select', key} {kw: 'move', key, toIndex} {kw: 'resize', lebar_kiri}
     * {kw: 'size', w, h, pages, overflowX}; sent to it: {kw: 'select-ui', key}.
     */
    function kwitansiCanvas($window, $timeout)
    {
        const PAGE = { w: 1122.52, h: 793.7 };

        return {
            restrict: 'E',
            scope: {
                html: '<',
                selected: '<',
                zoom: '<',
                onEvent: '&'
            },
            template: '<div class="kw-canvas-stage"></div>',
            link: (scope, element) => {
                const root = element[0];
                const stage = root.querySelector('.kw-canvas-stage');
                const frames = [makeFrame(), makeFrame()];
                let visible = -1;      // index of the frame the person sees
                let pending = -1;      // index of the frame that is loading the newest document
                let size = { w: PAGE.w, h: PAGE.h };
                let scale = 1;

                function makeFrame()
                {
                    const frame = $window.document.createElement('iframe');

                    // The sandbox attribute has to be there before anything is loaded into the frame.
                    frame.setAttribute('sandbox', 'allow-scripts');
                    frame.setAttribute('title', 'Kwitansi');
                    frame.className = 'kw-canvas-frame';
                    frame.style.visibility = 'hidden';
                    stage.appendChild(frame);

                    return frame;
                }

                function indexOfSource(source)
                {
                    return frames.findIndex(f => f.contentWindow === source);
                }

                function onMessage(e)
                {
                    const from = indexOfSource(e.source);
                    const msg = e.data;

                    if (from < 0 || !msg || typeof msg.kw !== 'string')
                    {
                        return;
                    }

                    scope.$evalAsync(() => {
                        if (msg.kw === 'ready')
                        {
                            if (from === pending)
                            {
                                swap(from);
                            }
                            else if (from === visible)
                            {
                                sendSelection(); // the visible frame was reloaded with a newer document
                            }
                            return;
                        }

                        // A page that is still loading may only report its size (to resize the stage early).
                        if (from !== visible && !(from === pending && msg.kw === 'size'))
                        {
                            return;
                        }

                        if (msg.kw === 'size')
                        {
                            size = { w: Math.max(msg.w || 0, PAGE.w), h: Math.max(msg.h || 0, PAGE.h) };
                            layout();
                        }

                        scope.onEvent({ msg: msg });
                    });
                }

                function swap(index)
                {
                    const old = visible;

                    visible = index;
                    pending = -1;
                    frames[index].style.visibility = 'visible';
                    frames[index].style.zIndex = 2;

                    if (old > -1)
                    {
                        frames[old].style.visibility = 'hidden';
                        frames[old].style.zIndex = 1;
                        frames[old].removeAttribute('srcdoc'); // free the old document
                    }

                    layout();
                    sendSelection();
                    root.classList.add('kw-canvas-ready');
                }

                function sendSelection()
                {
                    if (visible > -1 && frames[visible].contentWindow)
                    {
                        frames[visible].contentWindow.postMessage({ kw: 'select-ui', key: scope.selected || 'page' }, '*');
                    }
                }

                // Fit the page into the available width (never above 100%), or use the chosen zoom.
                function layout()
                {
                    const available = Math.max(root.clientWidth - 2, 200);
                    const fit = Math.min(1, available / size.w);

                    const previous = scale;
                    scale = typeof scope.zoom === 'number' && scope.zoom > 0 ? scope.zoom : fit;

                    if (scale !== previous)
                    {
                        scope.$evalAsync(() => scope.onEvent({ msg: { kw: 'scale', scale: scale } }));
                    }

                    stage.style.width = Math.ceil(size.w * scale) + 'px';
                    stage.style.height = Math.ceil(size.h * scale) + 'px';

                    frames.forEach(frame => {
                        frame.style.width = Math.ceil(size.w) + 'px';
                        frame.style.height = Math.ceil(size.h) + 'px';
                        frame.style.transform = 'scale(' + scale + ')';
                    });
                }

                scope.$watch('html', html => {
                    if (typeof html !== 'string')
                    {
                        return;
                    }

                    pending = visible === 0 ? 1 : 0;
                    frames[pending].srcdoc = html;
                });

                scope.$watch('selected', sendSelection);
                scope.$watch('zoom', layout);

                $window.addEventListener('message', onMessage);

                let resizeObserver = null;
                if ($window.ResizeObserver)
                {
                    resizeObserver = new $window.ResizeObserver(() => $timeout(layout, 0, false));
                    resizeObserver.observe(root);
                }
                else
                {
                    $window.addEventListener('resize', layout);
                }

                layout();

                scope.$on('$destroy', () => {
                    $window.removeEventListener('message', onMessage);
                    $window.removeEventListener('resize', layout);
                    if (resizeObserver)
                    {
                        resizeObserver.disconnect();
                    }
                    frames.forEach(frame => frame.remove());
                });
            }
        };
    }

    /**
     * <span kwitansi-drag-text="variable.sisip">: dragging it onto a textarea inserts the text where it is
     * dropped (the browser does that natively and fires `input`, so ng-model stays in sync).
     */
    function kwitansiDragText()
    {
        return {
            restrict: 'A',
            link: (scope, element, attrs) => {
                const el = element[0];

                el.setAttribute('draggable', 'true');
                el.addEventListener('dragstart', e => {
                    e.dataTransfer.effectAllowed = 'copy';
                    e.dataTransfer.setData('text/plain', scope.$eval(attrs.kwitansiDragText));
                });
            }
        };
    }
})()
