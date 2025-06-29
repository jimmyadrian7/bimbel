import guru from "./html/guru.html";

(() => {
    "use strict";

    angular.module('app.module.guru')
        .run(appRun);

    appRun.$inject = ['routerHelper'];

    function appRun(routerHelper) {
        routerHelper.configureStates(getStates());
    }

    function getStates() {
        return [
            {
                state: 'guru',
                config: {
                    url: '/Guru',
                    template: guru,
                    controller: 'BaseGuruController',
                    controllerAs: 'vm',
                    title: 'Guru',
                    menu: 'guru'
                }
            }
        ];
    }
})()