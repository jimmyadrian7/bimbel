import web from "./html/web.html";

(() => {
    "use strict";

    angular.module('app.module.web')
        .run(appRun);

    appRun.$inject =['routerHelper'];

    function appRun(routerHelper)
    {
        routerHelper.configureStates(getStates());
    }

    function getStates()
    {
        return [
            {
                state: 'web',
                config: {
                    url: '/Web',
                    template: web,
                    controller: 'WebController',
                    controllerAs: 'vm',
                    title: 'Web',
                    menu: 'web'
                }
            }
        ];
    }
})()