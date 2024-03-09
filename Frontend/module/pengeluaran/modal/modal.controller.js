import transaksi from "./html/modal/transaksi.html";
import tarikHtml from "./html/modal/tarik.html";

(() => {
    "use strict";

    angular.module('app.module.pengeluaran.modal')
        .controller('ModalController', ModalController);

    ModalController.$inject = ['$stateParams', 'Modal', '$compile', '$scope', 'req', '$state', 'kursusOptions', 'moment', 'session', '$parse', 'logger'];

    function ModalController(stateParams, Modal, $compile, $scope, req, state, kursusOptions, moment, session, $parse, logger)
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
            {name: "Keterangan", value: "keterangan", required: true},
            {name: "Nominal", value: "nominal", type: 'number', table: true},
            {name: "Sisa", value: "sisa", type: 'number', table: true, hidden: true},
            {name: "Status", value: "status", type: 'selection', selection: statusOpt, table: true, hidden: true, hideDetail: true}
        ];

        vm.cicilanFields = [
            {name: 'Tanggal', value: 'tanggal', type: 'date'},
            {name: 'Nominal', value: 'nominal', type: 'number'},
            {name: 'Bukti', value: 'bukti', type: 'file'},
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
        vm.deleteCicilanData = deleteCicilanData;

        
        vm.tarikTabungan = tarikTabungan;
        vm.editPenarikanData = editPenarikanData;
        vm.updateStatusPenarikan = updateStatusPenarikan;
        vm.buatPenarikan = buatPenarikan;
        vm.deletePenarikanData = deletePenarikanData;

        vm.getStatusLabel = getStatusLabel;
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
                modal_id: vm.dataId,
                bukti: vm.modal.form.bukti,
                nominal: vm.modal.form.nominal,
                tanggal: vm.modal.form.tanggal
            };

            if (data.id)
            {
                req.put('cicilan_modal', data).then(response => {
                    Modal.getInstance(vm.myModal[0]).hide();
                    state.reload();
                });
            }
            else
            {
                req.post('cicilan_modal', data).then(response => {
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
            req.put('cicilan_modal', data).then(response => state.reload());
        }
        function editDataCicilan(idx)
        {
            vm.modal.form = vm.data.cicilan_modal[idx];
            vm.myModal = $compile(transaksi)($scope);
        }
        function deleteCicilanData(cicilan)
        {
            let data = {id: cicilan.id};
            req.del('cicilan_modal', data).then( response => {
                if (response)
                {
                    logger.success("Success");
                    state.reload();
                }
            });
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
            vm.modal.form = vm.data.tarik_modal[idx];
            vm.myModal = $compile(tarikHtml)($scope);
        }
        function updateStatusPenarikan(penarikan)
        {
            let data = {
                id: penarikan.id,
                status: 's'
            };
            req.put('tarik_modal', data).then(response => state.reload());
        }
        function deletePenarikanData(penarikan)
        {
            let data = {id: penarikan.id};
            req.del('tarik_modal', data).then( response => {
                if (response)
                {
                    logger.success("Success");
                    state.reload();
                }
            });
        }
        function buatPenarikan()
        {
            let data = {
                id: vm.modal.form.id,
                modal_id: vm.dataId,
                bukti: vm.modal.form.bukti,
                nominal: vm.modal.form.nominal,
                tanggal: vm.modal.form.tanggal
            };

            if (data.id)
            {
                req.put('tarik_modal', data).then(response => {
                    Modal.getInstance(vm.myModal[0]).hide();
                    state.reload();
                });
            }
            else
            {
                req.post('tarik_modal', data).then(response => {
                    Modal.getInstance(vm.myModal[0]).hide();
                    state.reload();
                });
            }
        }
    }
})()