(() => {
    "use strict";

    angular.module('app.layout')
        .controller('LoginController', LoginController);

    LoginController.$inject = ['req', '$rootScope', '$state', '$window'];

    function LoginController(req, $rootScope, state, $window)
    {
        let vm = this;

        vm.form = {
            username: "",
            password: ""
        };
        vm.login = login;
        vm.errorMsg = "";
        vm.backTowebsite = backTowebsite;

        function login()
        {
            if (!vm.form.username || !vm.form.password)
            {
                vm.errorMsg = "Username or Password cannot be empty";
                return;
            }

            vm.errorMsg = "";

            req.post("user/authenticate", vm.form).then(response => {
                $rootScope.loggedIn = true;
                state.go('profile');
            }).catch(err => {
                vm.errorMsg = err;
            });
        }

        function backTowebsite()
        {
            let baseUrl = $window.location.pathname.split('/admin')[0];

            if (baseUrl == "")
            {
                baseUrl = "/";
            }

            $window.location = baseUrl;
        }
    }
})()