import table from "./html/table.html";
import detail from "./html/detail.html";
import form from "./html/form.html";

(() => {
    "use strict";

    angular.module('app.module.broadcast')
        .run(appRun);

    appRun.$inject =['routerHelper', 'req'];

    function appRun(routerHelper, req)
    {
        routerHelper.configureStates(getStates());
    }

    function getStates()
    {
        return [
            {
                state: 'broadcast',
                config: {
                    url: '/Broadcast',
                    template: table,
                    controller: 'BroadcastController',
                    controllerAs: 'vm',
                    title: 'Broadcast',
                    menu: 'broadcast'
                }
            },
            {
                state: 'broadcast_detail',
                config: {
                    url: '/Broadcast/{dataId}',
                    template: detail,
                    controller: 'BroadcastController',
                    controllerAs: 'vm',
                    title: 'Detail Broadcast',
                    menu: 'broadcast'
                }
            },
            {
                state: 'broadcast_form',
                config: {
                    url: '/Broadcast/form/:dataId',
                    template: form,
                    controller: 'BroadcastController',
                    controllerAs: 'vm',
                    title: 'Form Broadcast',
                    menu: 'broadcast'
                }
            }
        ];
    }
})()