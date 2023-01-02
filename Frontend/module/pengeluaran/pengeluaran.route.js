import pengeluaran from "./html/pengeluaran.html";

(() => {
    "use strict";

    angular.module('app.module.pengeluaran')
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
                state: 'pengeluaran',
                config: {
                    url: '/Pengeluaran',
                    template: pengeluaran,
                    controller: 'BasePengeluaranController',
                    controllerAs: 'vm',
                    title: 'Pengeluaran',
                    menu: 'pengeluaran'
                }
            }
        ];
    }
})()