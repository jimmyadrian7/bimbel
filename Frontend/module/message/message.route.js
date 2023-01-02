import table from "./html/table.html";
import detail from "./html/detail.html";
import form from "./html/form.html";

(() => {
    "use strict";

    angular.module('app.module.message')
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
                state: 'message',
                config: {
                    url: '/Message',
                    template: table,
                    controller: 'MessageController',
                    controllerAs: 'vm',
                    title: 'Message',
                    menu: 'message'
                }
            },
            {
                state: 'message_detail',
                config: {
                    url: '/Message/{dataId}',
                    template: detail,
                    controller: 'MessageController',
                    controllerAs: 'vm',
                    title: 'Detail Message',
                    menu: 'message'
                }
            },
            {
                state: 'message_form',
                config: {
                    url: '/Message/form/:dataId',
                    template: form,
                    controller: 'MessageController',
                    controllerAs: 'vm',
                    title: 'Form Message',
                    menu: 'message'
                }
            }
        ];
    }
})()