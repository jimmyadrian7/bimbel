import transaksi from "./html/modal/transaksi.html";
import tarikHtml from "./html/modal/tarik.html";

(() => {
    "use strict";

    angular.module('app.module.pengeluaran.tabungan_aset')
        .controller('TabunganAsetController', TabunganAsetController);

    TabunganAsetController.$inject = ['$stateParams', 'Modal', '$compile', '$scope', 'req', '$state', 'kursusOptions', 'moment', 'session', '$parse'];

    function TabunganAsetController(stateParams, Modal, $compile, $scope, req, state, kursusOptions, moment, session, $parse)
    {
        let vm = this;
        let statusOpt = [
            {value: 'a', label: 'Aktif'},
            {value: 'c', label: 'Cicil'},
            {value: 'l', label: 'Lunas'}
        ];

        let statusPenarikanOpt = [
            {value: 'm', label: 'Menunggu Verifikasi'},
            {value: 's', label: 'Sukses'}
        ];

        vm.data = {};
        vm.modal = {form: {}};
        vm.dataId = stateParams.dataId;
        vm.isAdmin = session.isAdminCabang() || session.isSuperUser();
        

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
            {name: 'Bukti Pembayaran', value: 'bukti_pembayaran', type: 'file'},
            {name: "Status", value: "status", type: 'selection', selection: statusPenarikanOpt, table: true, hidden: true, hideDetail: true}
        ];

        vm.penarikanFields = [
            {name: "Tanggal", value: "tanggal", type: 'date', required: true},
            {name: "Nominal", value: "nominal", type: 'number', required: true},
            {name: "Bukti", value: "bukti", type: 'file', required: true},
            {name: "Status", value: "status", type: 'selection', selection: statusPenarikanOpt, table: true, hidden: true, hideDetail: true}
        ];

        vm.myModal = false;

        vm.tambahCicilan = tambahCicilan;
        vm.buatCicilan = buatCicilan;
        vm.updateStatusCicilan = updateStatusCicilan;
        vm.editDataCicilan = editDataCicilan;


        vm.getStatusLabel = getStatusLabel;

        vm.generateInvoice = generateInvoice;
        
        
        vm.tarikTabungan = tarikTabungan;
        vm.editPenarikanData = editPenarikanData;
        vm.updateStatusPenarikan = updateStatusPenarikan;
        vm.buatPenarikan = buatPenarikan;

        vm.getValue = getValue;
        vm.getLabel = getLabel;

        function tambahCicilan()
        {
            vm.modal.form = {tanggal: moment(new Date()).format("YYYY-MM-DD")};
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
        function updateStatusCicilan(cicilan)
        {
            let data = {
                id: cicilan.id,
                status: 's'
            };
            req.put('cicilan_aset', data).then(response => state.reload());
        }
        function editDataCicilan(idx)
        {
            vm.modal.form = vm.data.cicilan_aset[idx];
            vm.myModal = $compile(transaksi)($scope);
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


        function getValue(field, data)
        {
            if (!field)
            {
                return "-";
            }

            let result = $parse(field.value)(data);

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


        function tarikTabungan()
        {
            vm.modal.form = {tanggal: moment(new Date()).format("YYYY-MM-DD")};
            vm.myModal = $compile(tarikHtml)($scope);
        }
        function editPenarikanData(idx)
        {
            vm.modal.form = vm.data.penarikan[idx];
            vm.myModal = $compile(tarikHtml)($scope);
        }
        function updateStatusPenarikan(penarikan)
        {
            let data = {
                id: penarikan.id,
                status: 's'
            };
            req.put('penarikan', data).then(response => state.reload());
        }
        function buatPenarikan()
        {
            let data = {
                id: vm.modal.form.id,
                tabungan_aset_id: vm.dataId,
                bukti: vm.modal.form.bukti,
                nominal: vm.modal.form.nominal,
                tanggal: vm.modal.form.tanggal
            };

            if (data.id)
            {
                req.put('penarikan', data).then(response => {
                    Modal.getInstance(vm.myModal[0]).hide();
                    state.reload();
                });
            }
            else
            {
                req.post('penarikan', data).then(response => {
                    Modal.getInstance(vm.myModal[0]).hide();
                    state.reload();
                });
            }
        }
    }
})()