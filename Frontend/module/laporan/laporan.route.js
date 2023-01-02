import laporan from "./html/laporan.html";

(() => {
    "use strict";

    angular.module('app.module.laporan')
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
                state: 'laporan',
                config: {
                    url: '/Laporan',
                    template: laporan,
                    controller: 'LaporanController',
                    controllerAs: 'vm',
                    title: 'Laporan',
                    menu: 'laporan'
                }
            }
        ];
    }
})()