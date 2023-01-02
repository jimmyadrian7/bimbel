import table from "./html/table.html";
import detail from "./html/detail.html";
import form from './html/form.html';

(() => {
    "use strict";

    angular.module('app.module.pembayaran.iuran')
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
                state: 'pembayaran.iuran',
                config: {
                    url: '/Iuran',
                    template: table,
                    controller: 'IuranController',
                    controllerAs: 'vm',
                    title: 'Iuran',
                    menu: 'pembayaran',
                    nav: 'iuran'
                }
            },
            {
                state: 'pembayaran.iuran_detail',
                config: {
                    url: '/Iuran/{dataId}',
                    template: detail,
                    controller: 'IuranController',
                    controllerAs: 'vm',
                    title: 'Detail Iuran',
                    menu: 'pembayaran',
                    nav: 'iuran'
                }
            },
            {
                state: 'pembayaran.iuran_form',
                config: {
                    url: '/Iuran/form/:dataId',
                    template: form,
                    controller: 'IuranController',
                    controllerAs: 'vm',
                    title: 'Form Iuran',
                    menu: 'pembayaran',
                    nav: 'iuran'
                }
            }
        ];
    }
})()