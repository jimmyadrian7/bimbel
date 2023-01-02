(() => {
    "use strict";

    angular.module('app.core')
        .run(appRun);

    appRun.$inject = ['routerHelper'];

    function appRun(routerHelper)
    {
        var otherwise = '/404';
        routerHelper.configureStates(getStates(), otherwise);
    }

    function getStates()
    {
        return [{
            state: '404',
            config: {
                url: '/404',
                title: '404 Not Found',
                templateUrl: 'admin/static/template/404'
            }
        }];
    }
})()