import Modal from "bootstrap/js/dist/modal";
import modal from "./html/modal.html";

(() => {
    "use strict";

    angular.module('app.module.konfigurasi.user')
        .controller('UserController', UserController);

    UserController.$inject = ['$stateParams', '$compile', '$scope', 'logger', 'req', '$state'];

    function UserController(stateParams, $compile, $scope, logger, req, state)
    {
        let vm = this;
        
        vm.data = {};
        vm.form = {};
        vm.dataId = stateParams.dataId;
        vm.fields = [
            {name: "Username", value: "username", table: true},
            {name: "Nama", value: "orang.nama", table: true},
            {name: "Password", value: "unenpass", hidden: true}
        ];
        
        vm.myModal = false;
        vm.gantiPass = gantiPass;
        vm.saveUser = saveUser;

        function gantiPass()
        {
            vm.myModal = $compile(modal)($scope);
        }

        function saveUser()
        {
            if (!vm.form.password)
            {
                logger.error('Password cannot be empty');
                return;
            }

            let data = {
                id: vm.data.id,
                password: vm.form.password
            };

            req.put('user', data).then(response => {
                Modal.getInstance(vm.myModal[0]).hide();
                state.reload();
            });
        }
    }
})()