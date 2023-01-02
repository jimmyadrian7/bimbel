import table from "./html/table.html";
import detail from "./html/detail.html";
import form from './html/form.html';

(() => {
    "use strict";

    angular.module('app.module.pembayaran.pembiayaan')
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
                state: 'pembayaran.pembiayaan',
                config: {
                    url: '/Pembiayaan',
                    template: table,
                    controller: 'PembiayaanController',
                    controllerAs: 'vm',
                    title: 'Pembiayaan',
                    menu: 'pembayaran',
                    nav: 'pembiayaan'
                }
            },
            {
                state: 'pembayaran.pembiayaan_detail',
                config: {
                    url: '/Pembiayaan/{dataId}',
                    template: detail,
                    controller: 'PembiayaanController',
                    controllerAs: 'vm',
                    title: 'Detail Pembiayaan',
                    menu: 'pembayaran',
                    nav: 'pembiayaan'
                }
            },
            {
                state: 'pembayaran.pembiayaan_form',
                config: {
                    url: '/Pembiayaan/form/:dataId',
                    template: form,
                    controller: 'PembiayaanController',
                    controllerAs: 'vm',
                    title: 'Form Pembiayaan',
                    menu: 'pembayaran',
                    nav: 'pembiayaan'
                }
            }
        ];
    }
})()