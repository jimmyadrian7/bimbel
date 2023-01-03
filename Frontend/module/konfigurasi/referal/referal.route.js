import table from "./html/table.html";
import detail from "./html/detail.html";
import form from './html/form.html';

(() => {
    "use strict";

    angular.module('app.module.konfigurasi.referal')
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
                state: 'konfigurasi.referal',
                config: {
                    url: '/Referal',
                    template: table,
                    controller: 'ReferalController',
                    controllerAs: 'vm',
                    title: 'Referal',
                    menu: 'konfigurasi',
                    nav: 'referal'
                }
            },
            {
                state: 'konfigurasi.referal_detail',
                config: {
                    url: '/Referal/{dataId}',
                    template: detail,
                    controller: 'ReferalController',
                    controllerAs: 'vm',
                    title: 'Detail Referal',
                    menu: 'konfigurasi',
                    nav: 'referal'
                }
            },
            {
                state: 'konfigurasi.referal_form',
                config: {
                    url: '/Referal/form/:dataId',
                    template: form,
                    controller: 'ReferalController',
                    controllerAs: 'vm',
                    title: 'Form Referal',
                    menu: 'konfigurasi',
                    nav: 'referal'
                }
            }
        ];
    }
})()