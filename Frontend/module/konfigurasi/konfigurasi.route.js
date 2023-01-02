import konfigurasi from "./html/konfigurasi.html";

(() => {
    "use strict";

    angular.module('app.module.konfigurasi')
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
                state: 'konfigurasi',
                config: {
                    url: '/Konfigurasi',
                    template: konfigurasi,
                    controller: 'KonfigurasiController',
                    controllerAs: 'vm',
                    title: 'Konfigurasi',
                    menu: 'konfigurasi'
                }
            }
        ];
    }
})()