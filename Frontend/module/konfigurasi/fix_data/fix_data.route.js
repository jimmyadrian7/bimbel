import home from "./html/home.html";

(() => {
    "use strict";

    angular.module('app.module.konfigurasi.fix_data')
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
                state: 'konfigurasi.fix_data',
                config: {
                    url: '/Fix Data',
                    template: home,
                    controller: 'FixDataController',
                    controllerAs: 'vm',
                    title: 'Fix Data',
                    menu: 'konfigurasi',
                    nav: 'fix_data'
                }
            },
        ];
    }
})()