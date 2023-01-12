(() => {
    "use strict";

    angular.module('app.layout')
        .controller('SidebarController', SidebarController);

    SidebarController.$inject = ['$state', 'routerHelper', 'config', 'req', '$rootScope', 'session'];

    function SidebarController($state, routerHelper, config, req, $rootScope, session)
    {
        let vm = this;
        // let states = routerHelper.getStates();
        
        vm.title = config.appTitle;
        
        vm.isCurrent = isCurrent;
        vm.go = go;
        vm.logout = logout;
        vm.navRoutes = [];

        activate();


        function activate()
        {
            getNavRoutes();
        }

        function getNavRoutes()
        {
            let userSession = session.getSession();
            vm.navRoutes = userSession.menu.filter(value => !value.parent);
        }

        function isCurrent(menu)
        {
            return $state.current.menu == menu.kode ? "active" : "";
        }

        function go(menu)
        {
            $state.go(menu.kode);
        }

        function logout()
        {
            req.post('user/current/logout', {}).then(response => {
                session.setSession({});
                $rootScope.loggedIn = false;
            });
        }
    }
})()