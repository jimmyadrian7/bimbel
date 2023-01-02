import siswa from "./html/siswa.html";

(() => {
    "use strict";

    angular.module('app.module.siswa')
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
                state: 'siswa',
                config: {
                    url: '/Siswa',
                    template: siswa,
                    controller: 'BaseSiswaController',
                    controllerAs: 'vm',
                    title: 'Siswa',
                    menu: 'siswa'
                }
            }
        ];
    }
})()