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
        let statusOpt = [
            {value: 'a', label: 'Aktif'},
            {value: 'n', label: 'Nonaktif'}
        ];

        vm.fields = [
            {name: "Username", value: "username", table: true, required: true},
            {name: "Nama", value: "orang.nama", table: true, hideDetail: true, required: true},
            {name: "Password", value: "unenpass", hidden: true},
            {name: "Status", value: "status", type: 'selection', selection: statusOpt, hidden: true, table: true, hideDetail: true}
        ];
        
        vm.myModal = false;
        vm.gantiPass = gantiPass;
        vm.saveUser = saveUser;

        vm.getLabel = getLabel;

        vm.cabangAble = cabangAble;
        vm.isCabang = isCabang;
        vm.adminCabang = adminCabang;
        vm.removeAdminCabang = removeAdminCabang;

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

        function getLabel(val)
        {
            for (let index = 0; index < statusOpt.length; index++) {
                if (statusOpt[index].value == val)
                {
                    return statusOpt[index].label;
                }
            }
        }

        function cabangAble()
        {
            let result = false;

            if (vm.data.jenis_user == 'u' && vm.data.role)
            {
                vm.data.role.forEach(value => {
                    if (value.kode == 'G')
                    {
                        result = true;
                    }
                });
            }

            return result;
        }

        function isCabang()
        {
            let result = false;

            if (vm.data.jenis_user == "c")
            {
                result = true;
            }

            return result;
        }

        function adminCabang()
        {
            let data = {
                id: vm.dataId,
                jenis_user: 'c'
            };
            req.put('user', data).then(() => state.reload());
        }

        function removeAdminCabang()
        {
            let data = {
                id: vm.dataId,
                jenis_user: 'u'
            };
            req.put('user', data).then(() => state.reload());
        }
    }
})()