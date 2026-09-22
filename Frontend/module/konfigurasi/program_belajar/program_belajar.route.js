import table from "./html/table.html";
import detail from "./html/detail.html";
import form from './html/form.html';

(() => {
    "use strict";

    angular.module('app.module.konfigurasi.program_belajar')
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
                state: 'konfigurasi.program_belajar',
                config: {
                    url: '/ProgramBelajar',
                    template: table,
                    controller: 'ProgramBelajarController',
                    controllerAs: 'vm',
                    title: 'Program Belajar',
                    menu: 'konfigurasi',
                    nav: 'program_belajar'
                }
            },
            {
                state: 'konfigurasi.program_belajar_detail',
                config: {
                    url: '/ProgramBelajar/{dataId}',
                    template: detail,
                    controller: 'ProgramBelajarController',
                    controllerAs: 'vm',
                    title: 'Detail Program Belajar',
                    menu: 'konfigurasi',
                    nav: 'program_belajar'
                }
            },
            {
                state: 'konfigurasi.program_belajar_form',
                config: {
                    url: '/ProgramBelajar/form/:dataId',
                    template: form,
                    controller: 'ProgramBelajarController',
                    controllerAs: 'vm',
                    title: 'Form Program Belajar',
                    menu: 'konfigurasi',
                    nav: 'program_belajar'
                }
            }
        ];
    }
})()