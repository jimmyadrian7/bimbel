(() => {
    "use strict";

    angular.module('app.layout')
        .controller('ShellController', ShellController);

    ShellController.$inject = ['$rootScope', '$timeout', 'session', 'req', '$scope'];

    function ShellController($rootScope, $timeout, session, req, $scope)
    {
        let vm = this;
        vm.busyMessage = 'Please wait ...';
        
        $rootScope.showLoginPage = true;
        $rootScope.showSplash = true;

        $scope.$watch(() => $rootScope.loggedIn, watchLoggedIn);

        getUserLogin();

        function getUserLogin()
        {
            req.get("user/current/login").then(data => {
                session.setSession(data);
                $rootScope.showLoginPage = false;
                activate();
            }).catch(err => {
                $rootScope.showLoginPage = true;
                activate();
            });
        }

        function activate()
        {
            $timeout(function() {
                $rootScope.showSplash = false;
                vm.isHidden = false;
            }, 1000);
        }

        function watchLoggedIn(newVal, oldVal)
        {
            if (newVal != oldVal)
            {
                getUserLogin();
            }
        }
    }
})()