(() => {
    "use strict";

    angular.module('app.layout')
        .controller('breadcrumbController', breadcrumbController);

    breadcrumbController.$inject = ['$state', 'routerHelper'];

    function breadcrumbController(state, routerHelper)
    {
        let vm = this;

        vm.breadcrumb = [];

        vm.isLast = isLast;
        vm.isLastCss = isLastCss;
        vm.goState = goState;

        activate();

        function activate()
        {
            let currentState = state.$current;

            if (currentState.name != currentState.menu)
            {
                vm.breadcrumb = [getRouteTitle(currentState.menu), currentState.title];
            }
            else
            {
                vm.breadcrumb = [currentState.title];
            }
        }

        function getRouteTitle(routeName)
        {
            let route = routerHelper.getStates();
            
            for (let index = 0; index < route.length; index++) {
                if (route[index].name == routeName)
                {
                    return route[index].title;
                }
            }
        }

        function isLast(idx)
        {
            return idx == (vm.breadcrumb.length - 1);
        }

        function isLastCss(idx)
        {
            if (isLast(idx))
            {
                return "text-dark active";
            }
            
            return "";
        }

        function goState(lastState)
        {
            // state.go(lastState);
        }
    }
})()