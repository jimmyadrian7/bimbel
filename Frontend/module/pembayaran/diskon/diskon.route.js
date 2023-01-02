import table from "./html/table.html";
import detail from "./html/detail.html";
import form from './html/form.html';

(() => {
    "use strict";

    angular.module('app.module.pembayaran.diskon')
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
                state: 'pembayaran.diskon',
                config: {
                    url: '/Diskon',
                    template: table,
                    controller: 'DiskonController',
                    controllerAs: 'vm',
                    title: 'Diskon',
                    menu: 'pembayaran',
                    nav: 'diskon'
                }
            },
            {
                state: 'pembayaran.diskon_detail',
                config: {
                    url: '/Diskon/{dataId}',
                    template: detail,
                    controller: 'DiskonController',
                    controllerAs: 'vm',
                    title: 'Detail Diskon',
                    menu: 'pembayaran',
                    nav: 'diskon'
                }
            },
            {
                state: 'pembayaran.diskon_form',
                config: {
                    url: '/Diskon/form/:dataId',
                    template: form,
                    controller: 'DiskonController',
                    controllerAs: 'vm',
                    title: 'Form Diskon',
                    menu: 'pembayaran',
                    nav: 'diskon'
                }
            }
        ];
    }
})()