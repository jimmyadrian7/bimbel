import laporan from "./html/laporan.html";

(() => {
    "use strict";

    angular.module('app.module.laporan.gaji_guru')
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
                state: 'laporan.gaji_guru',
                config: {
                    url: '/Gaji Guru',
                    template: laporan,
                    controller: 'GajiGuruController',
                    controllerAs: 'vm',
                    title: 'Gaji Guru',
                    menu: 'laporan',
                    nav: 'gaji_guru'
                }
            }
        ];
    }
})()