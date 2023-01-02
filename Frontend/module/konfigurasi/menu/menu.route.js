import table from "./html/table.html";
import detail from "./html/detail.html";
import form from './html/form.html';

(() => {
    "use strict";

    angular.module('app.module.konfigurasi.menu')
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
                state: 'konfigurasi.menu',
                config: {
                    url: '/Menu',
                    template: table,
                    controller: 'MenuController',
                    controllerAs: 'vm',
                    title: 'Menu',
                    menu: 'konfigurasi',
                    nav: 'menu'
                }
            },
            {
                state: 'konfigurasi.menu_detail',
                config: {
                    url: '/Menu/{dataId}',
                    template: detail,
                    controller: 'MenuController',
                    controllerAs: 'vm',
                    title: 'Detail Menu',
                    menu: 'konfigurasi',
                    nav: 'menu'
                }
            },
            {
                state: 'konfigurasi.menu_form',
                config: {
                    url: '/Menu/form/:dataId',
                    template: form,
                    controller: 'MenuController',
                    controllerAs: 'vm',
                    title: 'Form Menu',
                    menu: 'konfigurasi',
                    nav: 'menu'
                }
            }
        ];
    }
})()