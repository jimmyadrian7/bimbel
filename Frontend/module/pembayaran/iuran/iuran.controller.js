import iuran_detail from "./html/modal/iuran_detail.html";

(() => {
    "use strict";

    angular.module('app.module.pembayaran.iuran')
        .controller('IuranController', IuranController);

    IuranController.$inject = ['$stateParams', '$scope', '$compile', 'req'];

    function IuranController(stateParams, $scope, $compile, req)
    {
        let vm = this;

        vm.dataId = stateParams.dataId;
        vm.fields = [
            {name: "Nama", value: "nama", table: true},
            {name: "Bulan", value: "bulan", type: 'number', table: true}
        ];

        vm.iuranDetailFields = [
            {name: "Nama", value: "pembiayaan.nama"},
            {name: "Qty", value: "qty", type: 'number'},
            {name: "Harga", value: "pembiayaan.harga", type: 'number'},
            {name: "Skip", value: "skip", type: 'boolean'},
            {name: "Total", value: "total", type: 'number'}
        ];

        vm.data = {iuran_detail: []};
        vm.modal = {};
        vm.myModal = false;
        vm.tambahPembiayaan = tambahPembiayaan;
        vm.fetchPembiayaan = fetchPembiayaan;
        vm.isAdded = isAdded;
        vm.addDetail = addDetail;
        vm.updateTotal = updateTotal;
        vm.removeDetail = removeDetail;

        function tambahPembiayaan()
        {
            vm.myModal = $compile(iuran_detail)($scope);
        }
        function fetchPembiayaan()
        {
            req.get('pembiayaans').then(data => {
                vm.modal.pembiayaan = data;
            });
        }
        function isAdded(pembiayaan)
        {
            let result = false;

            for (let index = 0; index < vm.data.iuran_detail.length; index++) {
                const s = vm.data.iuran_detail[index];
                
                if (s.pembiayaan_id == pembiayaan.id)
                {
                    result = true;
                    break;
                }
            }

            return result;
        }
        function addDetail(pembiayaan)
        {
            vm.data.iuran_detail.push({
                pembiayaan_id: pembiayaan.id,
                skip: 0,
                qty: 1,
                pembiayaan: pembiayaan,
                total: pembiayaan.harga
            });
        }
        function updateTotal(idx)
        {
            let iuran_detail = vm.data.iuran_detail[idx];
            vm.data.iuran_detail[idx].total = iuran_detail.qty * iuran_detail.pembiayaan.harga;
        }
        function removeDetail(idx)
        {
            vm.data.iuran_detail.splice(idx, 1);
        }
    }
})()