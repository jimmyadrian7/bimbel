import pembayaran from "./html/pembayaran.html";

(() => {
    "use strict";

    angular.module('app.module.pembayaran')
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
                state: 'pembayaran',
                config: {
                    url: '/Pembayaran',
                    template: pembayaran,
                    controller: 'PembayaranController',
                    controllerAs: 'vm',
                    title: 'Pembayaran',
                    menu: 'pembayaran'
                }
            }
        ];
    }
})()