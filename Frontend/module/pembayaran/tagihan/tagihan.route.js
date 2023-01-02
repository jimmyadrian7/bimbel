import table from "./html/table.html";
import detail from "./html/detail.html";
import form from './html/form.html';

(() => {
    "use strict";

    angular.module('app.module.pembayaran.tagihan')
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
                state: 'pembayaran.tagihan',
                config: {
                    url: '/Tagihan',
                    template: table,
                    controller: 'TagihanController',
                    controllerAs: 'vm',
                    title: 'Tagihan',
                    menu: 'pembayaran',
                    nav: 'tagihan'
                }
            },
            {
                state: 'pembayaran.tagihan_detail',
                config: {
                    url: '/Tagihan/{dataId}',
                    template: detail,
                    controller: 'TagihanController',
                    controllerAs: 'vm',
                    title: 'Detail Tagihan',
                    menu: 'pembayaran',
                    nav: 'tagihan'
                }
            },
            {
                state: 'pembayaran.tagihan_form',
                config: {
                    url: '/Tagihan/form/:dataId',
                    template: form,
                    controller: 'TagihanController',
                    controllerAs: 'vm',
                    title: 'Form Tagihan',
                    menu: 'pembayaran',
                    nav: 'tagihan'
                }
            }
        ];
    }
})()