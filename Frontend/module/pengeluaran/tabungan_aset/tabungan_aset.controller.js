import transaksi from "./html/modal/transaksi.html";

(() => {
    "use strict";

    angular.module('app.module.pengeluaran.tabungan_aset')
        .controller('TabunganAsetController', TabunganAsetController);

    TabunganAsetController.$inject = ['$stateParams', 'Modal', '$compile', '$scope', 'req', '$state', 'kursusOptions'];

    function TabunganAsetController(stateParams, Modal, $compile, $scope, req, state, kursusOptions)
    {
        let vm = this;
        let statusOpt = [
            {value: 'a', label: 'Aktif'},
            {value: 'c', label: 'Cicil'},
            {value: 'l', label: 'Lunas'}
        ];

        vm.data = {};
        vm.modal = {form: {}};
        vm.dataId = stateParams.dataId;
        

        vm.fields = [
            {name: "Tempat Kursus", value: "kursus_id", type: 'selection', selection: kursusOptions, table: true, required: true},
            {name: "Nama", value: "nama", table: true, required: true},
            {name: "Keterangan", value: "keterangan", required: true},
            {name: "Jumlah", value: "jumlah", type: 'number', table: true, required: true},
            {name: "Harga", value: "harga", type: 'number', table: true, required: true},
            {name: "Total", value: "total", type: 'number', table: true, hidden: true},
            {name: "Sisa", value: "sisa", type: 'number', table: true, hidden: true},
            {name: "Lama Cicil", value: "cicil", type: 'number', required: true},
            {name: "Status", value: "status", type: 'selection', selection: statusOpt, table: true, hidden: true, hideDetail: true}

        ];

        vm.cicilanFields = [
            {name: 'Tanggal', value: 'tanggal', type: 'date'},
            {name: 'Nominal', value: 'nominal', type: 'number'},
            {name: 'Bukti Pembayaran', value: 'bukti_pembayaran', type: 'file'}
        ];

        vm.myModal = false;

        vm.tambahCicilan = tambahCicilan;
        vm.buatCicilan = buatCicilan;
        
        vm.getStatusLabel = getStatusLabel;

        vm.generateInvoice = generateInvoice;

        vm.editData = editData;

        function tambahCicilan()
        {
            vm.modal.form = {};
            vm.myModal = $compile(transaksi)($scope);
        }
        function buatCicilan()
        {
            let data = {
                id: vm.modal.form.id,
                tabungan_aset_id: vm.dataId,
                bukti_pembayaran: vm.modal.form.bukti_pembayaran,
                nominal: vm.modal.form.nominal,
                tanggal: vm.modal.form.tanggal
            };

            if (data.id)
            {
                req.put('cicilan_aset', data).then(response => {
                    Modal.getInstance(vm.myModal[0]).hide();
                    state.reload();
                });
            }
            else
            {
                req.post('cicilan_aset', data).then(response => {
                    Modal.getInstance(vm.myModal[0]).hide();
                    state.reload();
                });
            }
        }

        function getStatusLabel(val)
        {
            for (let index = 0; index < statusOpt.length; index++) {
                if (statusOpt[index].value == val)
                {
                    return statusOpt[index].label;
                }
            }
        }

        function generateInvoice()
        {
            req.get(`generate/report/tabungan_aset/invoice/${vm.dataId}`).then(response => {
                vm.activePdf = {filename: "invoice.pdf", filetype: 'application/pdf', base64: response.data};
                let element = `<app-modal-preview value='vm.activePdf'></app-modal-preview>`;
                element = $compile(element)($scope);
            });
        }

        function editData(idx)
        {
            vm.modal.form = vm.data.cicilan_aset[idx];
            vm.myModal = $compile(transaksi)($scope);
        }
    }
})()