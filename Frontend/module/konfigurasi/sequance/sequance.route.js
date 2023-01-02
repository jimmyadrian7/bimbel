import table from "./html/table.html";
import detail from "./html/detail.html";
import form from './html/form.html';

(() => {
    "use strict";

    angular.module('app.module.konfigurasi.sequance')
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
                state: 'konfigurasi.sequance',
                config: {
                    url: '/Sequance',
                    template: table,
                    controller: 'SequanceController',
                    controllerAs: 'vm',
                    title: 'Sequance',
                    menu: 'konfigurasi',
                    nav: 'sequance'
                }
            },
            {
                state: 'konfigurasi.sequance_detail',
                config: {
                    url: '/Sequance/{dataId}',
                    template: detail,
                    controller: 'SequanceController',
                    controllerAs: 'vm',
                    title: 'Detail Sequance',
                    menu: 'konfigurasi',
                    nav: 'sequance'
                }
            },
            {
                state: 'konfigurasi.sequance_form',
                config: {
                    url: '/Sequance/form/:dataId',
                    template: form,
                    controller: 'SequanceController',
                    controllerAs: 'vm',
                    title: 'Form Sequance',
                    menu: 'konfigurasi',
                    nav: 'sequance'
                }
            }
        ];
    }
})()