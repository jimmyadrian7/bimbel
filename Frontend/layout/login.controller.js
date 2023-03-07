(() => {
    "use strict";

    angular.module('app.layout')
        .controller('LoginController', LoginController);

    LoginController.$inject = ['req', '$rootScope', '$state'];

    function LoginController(req, $rootScope, state)
    {
        let vm = this;

        vm.form = {
            username: "",
            password: ""
        };
        vm.login = login;
        vm.errorMsg = "";

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
                vm.errorMsg = err.data.exception.message;
            });
        }
    }
})()