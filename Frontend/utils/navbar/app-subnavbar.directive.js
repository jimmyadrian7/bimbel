import nav from "./html/navbar.html";

(() => {
    "use strict";

    angular.module('app.utils')
        .directive('appSubnavbar', appSubnavbar);

    appSubnavbar.$inject = ['$state', 'session'];

    function appSubnavbar(state, session)
    {
        let directive = {
            restrict: 'E',
            template: nav,
            controller: controllerFunc,
            controllerAs: 'vm',
            scope: true,
            replace: true,
            bindToController: true
        };

        return directive;

        function controllerFunc()
        {
            let vm = this;

            vm.menus = [];

        vm.goState = goState;
        vm.isCurrent = isCurrent;

        activate();

        function activate()
        {
            let menu = session.getGroupMenu(state.current.menu);
                vm.menus = menu.map(value => {
                    return {name: value.nama, list: `${state.current.menu}.${value.kode}`, nav: value.kode};
                });
            }

            function goState(menu)
            {
                state.go(menu.list);
            }

            function isCurrent(menu)
            {
                let result = "text-dark";
                
                if (state.current.nav == menu.nav)
                {
                    result = "text-primary";
                }
                
                return result;
            }
        }
    }
})()