(() => {
    "use strict";

    let core = angular.module('app.core');

    let config = {
        appErrorPrefix: '[Bimbel Error] ',
        appTitle: '学生一家'
    };
    configure.$inject = ['$logProvider', 'routerHelperProvider', 'exceptionHandlerProvider'];
    core.value('config', config);
    core.config(configure);

    compiler.$inject = ['$compileProvider'];
    core.config(compiler);

    toastrConfig.$inject = ['toastr'];
    core.config(toastrConfig);


    function configure($logProvider, routerHelperProvider, exceptionHandlerProvider) 
    {
        if ($logProvider.debugEnabled)
        {
            $logProvider.debugEnabled(true);
        }

        exceptionHandlerProvider.configure(config.appErrorPrefix);
        routerHelperProvider.configure({ docTitle: config.appTitle + ' | ' });
    }

    function compiler(compileProvider)
    {
        compileProvider.aHrefSanitizationWhitelist(/^\s*(https?|local|data|chrome-extension):/);
        compileProvider.imgSrcSanitizationWhitelist(/^\s*(https?|local|data|chrome-extension):/);
    }

    function toastrConfig(toastr)
    {
        toastr.options.timeOut = 4000;
        toastr.options.positionClass = 'toast-bottom-right';
    }

})()