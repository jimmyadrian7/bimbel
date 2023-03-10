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
                var newException;

                if (e.data && e.data.exception.length > 0 && Array.isArray(e.data.exception))
                {
                    newException = e.data.exception[0];
                    if (newException.code != 501)
                    {
                        logger.error(newException.message, e, "Error");
                    }
                    
                    return $q.reject(newException.message);
                }
                else
                {
                    newException = e.data.exception;
                    if (e.status != 501)
                    {
                        logger.error(newException.message, e, "Error");
                    }
                    
                    return $q.reject(newException.message);
                }

                return $q.reject(e);
            }
        }
    }
})()