import table from "./html/table.html";
import detail from "./html/detail.html";
import form from './html/form.html';

(() => {
    "use strict";

    angular.module('app.module.konfigurasi.kursus')
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
                state: 'konfigurasi.kursus',
                config: {
                    url: '/Kursus',
                    template: table,
                    controller: 'KursusController',
                    controllerAs: 'vm',
                    title: 'Kursus',
                    menu: 'konfigurasi',
                    nav: 'kursus'
                }
            },
            {
                state: 'konfigurasi.kursus_detail',
                config: {
                    url: '/Kursus/{dataId}',
                    template: detail,
                    controller: 'KursusController',
                    controllerAs: 'vm',
                    title: 'Detail Kursus',
                    menu: 'konfigurasi',
                    nav: 'kursus'
                }
            },
            {
                state: 'konfigurasi.kursus_form',
                config: {
                    url: '/Kursus/form/:dataId',
                    template: form,
                    controller: 'KursusController',
                    controllerAs: 'vm',
                    title: 'Form Kursus',
                    menu: 'konfigurasi',
                    nav: 'kursus'
                }
            }
        ];
    }
})()