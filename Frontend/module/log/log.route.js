import table from "./html/table.html";
import detail from "./html/detail.html";

(() => {
    "use strict";

    angular.module('app.module.log')
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
                state: 'log',
                config: {
                    url: '/Log',
                    template: table,
                    controller: 'LogController',
                    controllerAs: 'vm',
                    title: 'Log',
                    menu: 'log',
                }
            },
            {
                state: 'log_detail',
                config: {
                    url: '/Log/{dataId}',
                    template: detail,
                    controller: 'LogController',
                    controllerAs: 'vm',
                    title: 'Detail Log',
                    menu: 'log',
                }
            }
        ];
    }
})()