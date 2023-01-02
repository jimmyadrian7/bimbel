import laporan from "./html/laporan.html";

(() => {
    "use strict";

    angular.module('app.module.laporan.pengeluaran')
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
                state: 'laporan.pengeluaran',
                config: {
                    url: '/Pengeluaran',
                    template: laporan,
                    controller: 'LaporanPengeluaranController',
                    controllerAs: 'vm',
                    title: 'Pengeluaran',
                    menu: 'laporan',
                    nav: 'pengeluaran'
                }
            }
        ];
    }
})()