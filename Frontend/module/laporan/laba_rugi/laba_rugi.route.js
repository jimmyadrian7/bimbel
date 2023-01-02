import laporan from "./html/laporan.html";

(() => {
    "use strict";

    angular.module('app.module.laporan.laba_rugi')
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
                state: 'laporan.laba_rugi',
                config: {
                    url: '/Laba Rugi',
                    template: laporan,
                    controller: 'LabaRugiController',
                    controllerAs: 'vm',
                    title: 'Laba Rugi',
                    menu: 'laporan',
                    nav: 'laba_rugi'
                }
            }
        ];
    }
})()