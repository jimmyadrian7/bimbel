import table from "./html/table.html";
import detail from "./html/detail.html";
import form from './html/form.html';

(() => {
    "use strict";

    angular.module('app.module.konfigurasi.role')
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
                state: 'konfigurasi.role',
                config: {
                    url: '/Role',
                    template: table,
                    controller: 'RoleController',
                    controllerAs: 'vm',
                    title: 'Role',
                    menu: 'konfigurasi',
                    nav: 'role'
                }
            },
            {
                state: 'konfigurasi.role_detail',
                config: {
                    url: '/Role/{dataId}',
                    template: detail,
                    controller: 'RoleController',
                    controllerAs: 'vm',
                    title: 'Detail Role',
                    menu: 'konfigurasi',
                    nav: 'role'
                }
            },
            {
                state: 'konfigurasi.role_form',
                config: {
                    url: '/Role/form/:dataId',
                    template: form,
                    controller: 'RoleController',
                    controllerAs: 'vm',
                    title: 'Form Role',
                    menu: 'konfigurasi',
                    nav: 'role'
                }
            }
        ];
    }
})()