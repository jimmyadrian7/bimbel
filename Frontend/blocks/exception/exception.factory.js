(() => {
    "use strict";

    angular.module('blocks.exception').factory('exception', exception);

    exception.$inject = ['$q', 'logger'];

    function exception($q, logger)
    {
        return {
            catcher: catcher
        };

        function catcher()
        {
            return e => {
                let message = extractMessage(e);

                // Session-expired ("not logged in") failures are handled
                // separately (see ShellController / status 501) - don't
                // pile a toast on top of that flow. Everything else always
                // gets surfaced, whatever shape it came in.
                if (e && e.status !== 501)
                {
                    logger.error(message, e, "Error");
                }

                return $q.reject(message);
            }
        }

        function extractMessage(e)
        {
            const fallback = "Terjadi kesalahan. Silakan coba lagi.";

            if (!e)
            {
                return fallback;
            }

            // Network failure / request never reached the server / CORS
            // block: no response body at all.
            if (e.status === -1 || e.status === 0 || !e.data)
            {
                return "Tidak dapat terhubung ke server. Periksa koneksi anda.";
            }

            let body = e.data;

            // Some plain-text or HTML error page slipped through instead
            // of JSON (e.g. a PHP fatal error rendered as HTML). $http
            // leaves e.data as a raw string in that case.
            if (typeof body === 'string')
            {
                return fallback;
            }

            let exc = body.exception;

            if (!exc)
            {
                // App's own {exception: {...}} shape is missing entirely -
                // fall back to whatever message-ish field is present.
                return body.message || fallback;
            }

            // Backend always responds with a single exception object
            // ({exception: {message: ...}}), but be tolerant of an array
            // shape too (e.g. Slim's own debug-mode error renderer nests
            // the trace as an array) instead of assuming one or the other.
            if (Array.isArray(exc))
            {
                exc = exc[0];
            }

            if (!exc || !exc.message)
            {
                return fallback;
            }

            return exc.message;
        }
    }
})()