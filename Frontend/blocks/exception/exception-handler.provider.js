(() => {
    "use strict";

    angular.module('blocks.exception')
        .provider('exceptionHandler', exceptionHandlerProvider)
        .config(config);

    config.$inject = ["$provide"];
    extendExceptionHandler.$inject = ['$delegate', 'exceptionHandler', 'logger'];

    function exceptionHandlerProvider()
    {
        this.config = {
            appErrorPrefix: undefined
        };

        this.configure = function(appErrorPrefix) {
            this.config.appErrorPrefix = appErrorPrefix;
        };

        this.$get = function() {
            return { config: this.config };
        };
    }

    function config($provide)
    {
        $provide.decorator('$exceptionHandler', extendExceptionHandler);
    }

    function extendExceptionHandler($delegate, exceptionHandler, logger)
    {
        return function(exception, cause)
        {
            let appErrorPrefix = exceptionHandler.config.appErrorPrefix || '';
            let errorData = { exception: exception, cause: cause };

            // logger.error(exception, errorData);
            
            $delegate(exception, cause);
        }
    }
})()