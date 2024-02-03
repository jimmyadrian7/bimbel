import modal from "./html/modal.html";

(() => {
    "use strict";

    angular.module('app.module.siswa.deposit')
        .controller('DepositController', DepositController);

    DepositController.$inject = ['$stateParams', '$compile', '$scope', 'logger', 'req', '$state', 'Modal', '$parse', 'session'];

    function DepositController(stateParams, $compile, $scope, logger, req, state, Modal, $parse, session)
    {
        let vm = this;
        let statusOpt = [
            {label: "Aktif", value: "a"},
            {label: "Terima", value: "t"},
            {label: "Hangus", value: "h"}
        ];

        vm.isSiswa = session.isSiswa();
        vm.myModal = false;
        vm.modal = {};
        vm.data = {};
        vm.dataId = stateParams.dataId;

        vm.status_field = {name: "Status", value: "status", type: "selection", selection: statusOpt, table: true, hidden: true, hideDetail: true};
        vm.fields = [
            {name: "Tanggal", value: "tanggal", table: true, type: "date"},
            {name: "Tanggal Keluar", value: "tanggal_keluar", table: true, type: "date"},
            {name: "Nominal", value: "nominal", table: true, type: "number"},
            {name: "Siswa", value: "siswa.orang.nama", table: true, hidden: true},
            {name: "Bukti Pembayaran", value: "bukti_pembayaran", type: "file", table: true},
            vm.status_field
        ];

        vm.uploadBukti = uploadBukti;
        vm.saveBukti = saveBukti;
        vm.getValue = getValue;
        vm.updateStatus = updateStatus;

        function uploadBukti()
        {
            vm.myModal = $compile(modal)($scope);
        }

        function saveBukti()
        {
            if (!vm.modal.bukti)
            {
                logger.error("bukti tidak boleh kosong");
                return;
            }

            let data = {
                id: vm.data.id,
                bukti_pembayaran: vm.modal.bukti
            };

            req.put('deposit', data).then(response => {
                Modal.getInstance(vm.myModal[0]).hide();
                state.reload();
            });
        }

        function getValue(field)
        {
            let result = $parse(field.value)(vm.data);

                if (field.type == 'selection')
                {
                    result = getLabel(result, field.selection);
                }

                return result || "-";
        }

        function getLabel(val, arr)
        {
            for (let index = 0; index < arr.length; index++) {
                if (arr[index].value == val)
                {
                    return arr[index].label;
                }
            }
        }

        function updateStatus(status)
        {
            let data = {
                id: vm.data.id,
                status: status
            };

            req.put('deposit', data).then(response => state.reload());
        }
    }
})()