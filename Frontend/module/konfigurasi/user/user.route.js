import table from "./html/table.html";
import detail from "./html/detail.html";
import form from './html/form.html';

(() => {
    "use strict";

    angular.module('app.module.konfigurasi.user')
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
                state: 'konfigurasi.user',
                config: {
                    url: '/User',
                    template: table,
                    controller: 'UserController',
                    controllerAs: 'vm',
                    title: 'User',
                    menu: 'konfigurasi',
                    nav: 'user'
                }
            },
            {
                state: 'konfigurasi.user_detail',
                config: {
                    url: '/User/{dataId}',
                    template: detail,
                    controller: 'UserController',
                    controllerAs: 'vm',
                    title: 'Detail User',
                    menu: 'konfigurasi',
                    nav: 'user'
                }
            },
            {
                state: 'konfigurasi.user_form',
                config: {
                    url: '/User/form/:dataId',
                    template: form,
                    controller: 'UserController',
                    controllerAs: 'vm',
                    title: 'Form User',
                    menu: 'konfigurasi',
                    nav: 'user'
                }
            }
        ];
    }
})()