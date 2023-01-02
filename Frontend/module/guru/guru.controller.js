import modalGaji from "./html/modal/modal-gaji.html";
import tunjangan from "./html/modal/tunjangan.html";

(() => {
    "use strict";

    angular.module('app.module.guru')
        .controller('GuruController', GuruController);

    GuruController.$inject = ['$stateParams', 'agamaOptions', '$compile', '$scope', 'req', '$state', '$parse', 'Modal', 'logger', 'moment'];

    function GuruController(stateParams, agamaOptions, $compile, scope, req, state, $parse, Modal, logger, moment)
    {
        let vm = this;
        let jenisKelamin = [
            {label: "Laki laki", value: "l"},
            {label: "Perempuan", value: "p"}
        ];

        vm.modal = {siswa: {}, added: []};
        vm.siswa = [];
        vm.modalElement = false;
        vm.data = {};
        vm.dataId = stateParams.dataId;

        vm.statusOpt = [
            {value: 'a', label: 'Aktif'},
            {value: 'n', label: 'Nonaktif'}
        ];
        vm.fields = [
            {name: "Nama", value: "orang.nama", table: true},
            {name: "Jenis Kelamin", value: "orang.jenis_kelamin", type: 'selection', selection: jenisKelamin, table: true, hidden: true, hideDetail: true},
            {name: "No. HP", value: "orang.no_hp", table: true, hidden: true, hideDetail: true}
        ];

        vm.additional = {};
        vm.additional.tunjanganFields = [
            {name: 'Nama', value: 'nama'},
            {name: 'Nominal', value: 'nominal', type: 'number'}
        ];
        vm.additional.siswaFields = [
            {name: 'No. Formulir', value: 'no_formulir'},
            {name: 'Tanggal Pendaftaran', value: 'tanggal_pendaftaran', type: 'date'},
            {name: 'Nama', value: 'orang.nama'}
        ];
        vm.additional.gajiFields = [
            {name: 'Total Siswa', value: 'total_siswa', type: 'number'},
            {name: 'Gaji', value: 'total', type: 'number'},
            {name: 'Tanggal', value: 'tanggal', type: 'date'}
        ];
        vm.additional.detailFields = [
            [
                {name: "Jenis Kelamin", value: "orang.jenis_kelamin", type: 'selection', selection: jenisKelamin},
                {name: "Agama", value: "orang.agama_id", type: 'selection', selection: agamaOptions},
                {name: "No. HP", value: "orang.no_hp", table: true},
                {name: "Email", value: "orang.email"},
                {name: "Alamat", value: "orang.alamat"}
            ],
            [
                {name: "Tempat Lahir", value: "orang.tempat_lahir"},
                {name: "Tanggal Lahir", value: "orang.tanggal_lahir", type: 'date'},
                {name: "Hobi", value: "orang.hobi"}
            ]
        ];

        vm.getValue = getValue;
        vm.getLabel = getLabel;

        vm.generateGaji = generateGaji;
        vm.genGaji = genGaji;

        vm.fetchDataSiswa = fetchDataSiswa;

        vm.tambahTunjangan = tambahTunjangan;
        vm.addTunjangan = addTunjangan;
        vm.deleteTunjangan = deleteTunjangan;

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

        function generateGaji()
        {
            vm.modal.tanggal = moment(new Date()).format("YYYY-MM-DD");
            vm.modalElement = $compile(modalGaji)(scope);
        }
        function genGaji()
        {
            if (!vm.modal.tanggal)
            {
                logger.error("Date cannot be empty");
                return;
            }

            let data = {
                date: vm.modal.tanggal
            };            
            req.post('guru/generate/gaji', data).then(response => {
                Modal.getInstance(vm.modalElement[0]).hide();
                state.reload();
            });
        }

        function fetchDataSiswa()
        {
            req.get(`guru/data/siswa/${stateParams.dataId}`).then(data => vm.siswa = data);
        }

        function tambahTunjangan()
        {
            vm.modalElement = $compile(tunjangan)(scope);
        }
        function addTunjangan()
        {
            let data = {
                guru_id: vm.dataId,
                nama: vm.modal.nama,
                nominal: vm.modal.nominal
            };

            req.post('tunjangan_guru', data).then(response => {
                Modal.getInstance(vm.modalElement[0]).hide();
                state.reload();
            });
        }
        function deleteTunjangan(tunjangan)
        {
            let data = {
                id: tunjangan.id
            }
            req.del('tunjangan_guru', data).then(response => {
                state.reload();
            });
        }
    }
})()