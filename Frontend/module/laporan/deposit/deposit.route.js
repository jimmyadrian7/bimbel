import laporan from "./html/laporan.html";

(() => {
    "use strict";

    angular.module('app.module.laporan.deposit')
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
                state: 'laporan.deposit',
                config: {
                    url: '/Deposit',
                    template: laporan,
                    controller: 'LaporanDepositController',
                    controllerAs: 'vm',
                    title: 'Deposit',
                    menu: 'laporan',
                    nav: 'deposit'
                }
            }
        ];
    }
})()