import detail from "./html/detail.html";
import form from './html/form.html';

(() => {
    "use strict";

    angular.module('app.module.web.konfigurasi')
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
                state: 'web.konfigurasi',
                config: {
                    url: '/Konfigurasi',
                    template: detail,
                    controller: 'WebKonfigurasiController',
                    controllerAs: 'vm',
                    title: 'Konfigurasi',
                    menu: 'web',
                    nav: 'konfigurasi'
                }
            },
            {
                state: 'web.konfigurasi_form',
                config: {
                    url: '/Konfigurasi/form/:dataId',
                    template: form,
                    controller: 'WebKonfigurasiController',
                    controllerAs: 'vm',
                    title: 'Form Konfigurasi',
                    menu: 'web',
                    nav: 'konfigurasi'
                }
            }
        ];
    }
})()