import modalGaji from "./html/modal/modal-gaji.html";
import tunjangan from "./html/modal/tunjangan.html";
import potongan_gaji from "./html/modal/potongan_gaji.html";
import pilihHari from "./html/modal/pilih-hari.html";
import showGuru from "./html/modal/show-guru.html";
import kursusHtml from "./html/modal/kursus.html";
import modalSlipGaji from "./html/modal/modal-slip-gaji.html";
import previewSlipGaji from "./html/modal/preview-slip-gaji.html";

(() => {
    "use strict";

    angular.module('app.module.guru.guru')
        .controller('GuruController', GuruController);

    GuruController.$inject = [
        '$stateParams', 'agamaOptions', '$compile', '$scope', 'req', '$state',
        '$parse', 'Modal', 'logger', 'moment', 'session', 'kursusOptions', '$element'
    ];

    function GuruController(
        stateParams, agamaOptions, $compile, scope,
        req, state, $parse, Modal, logger, moment, session, kursusOptions, $element
    ) {
        let vm = this;
        let jenisKelamin = [
            { label: "Laki laki", value: "l" },
            { label: "Perempuan", value: "p" }
        ];
        let hariOpt = [
            { value: "1", label: "Senin" },
            { value: "2", label: "Selasa" },
            { value: "3", label: "Rabu" },
            { value: "4", label: "Kamis" },
            { value: "5", label: "Jumat" },
            { value: "6", label: "Sabtu" },
            { value: "7", label: "Minggu" }
        ];

        vm.siswaStatusOpt = [
            { value: "", label: "All" },
            { value: "b", label: "Baru" },
            { value: "a", label: "Aktif" },
            { value: "p", label: "Pengembalian" },
            { value: "n", label: "Berhenti" }
        ];

        vm.myPdf = true;
        vm.modal = { siswa: {}, added: [], listGuru: [], kursus: [] };
        vm.siswa = [];
        vm.modalElement = false;
        vm.data = { kursus: [] };
        vm.customData = { siswa: [] };
        vm.dataId = stateParams.dataId;
        vm.isSuperUser = session.isSuperUser();
        vm.filterSiswa = "";

        vm.statusOpt = [
            { value: 'a', label: 'Aktif' },
            { value: 'n', label: 'Nonaktif' }
        ];
        vm.fields = [
            { name: "Nama", value: "orang.nama", table: true, hideDetail: true, required: true },
            { name: "Jenis Kelamin", value: "orang.jenis_kelamin", type: 'selection', selection: jenisKelamin, table: true, hidden: true, hideDetail: true, required: true },
            { name: "No. HP", value: "orang.no_hp", table: true, hidden: true, hideDetail: true, required: true },
            { name: "Status", value: "status", type: 'selection', selection: vm.statusOpt, table: true, hidden: true, hideDetail: true },
            { name: "Profile Picture", value: "orang.pp", type: "file", hideDetail: true, required: true },
            { name: "Jabatan", value: "jabatan", table: false },
        ];

        vm.additional = {};
        vm.additional.tunjanganFields = [
            { name: 'Nama', value: 'nama' },
            { name: 'Jumlah', value: 'jumlah', type: 'number' },
            { name: 'Nominal', value: 'nominal', type: 'number' }
        ];
        vm.additional.siswaFields = [
            { name: 'No. Formulir', value: 'no_formulir' },
            { name: 'Tanggal Pendaftaran', value: 'tanggal_pendaftaran', type: 'date' },
            { name: 'Nama', value: 'orang.nama' },
            { name: "Tempat Kursus", value: "kursus_id", type: "selection", selection: kursusOptions }
        ];
        vm.additional.gajiFields = [
            { name: 'Total Siswa', value: 'total_siswa', type: 'number' },
            { name: 'Gaji', value: 'total', type: 'number' },
            { name: 'Bulan', value: 'bulan' },
            { name: 'Tahun', value: 'tahun' },
            { name: 'Tanggal Gajian', value: 'tanggal', type: 'date' }
        ];
        vm.additional.potonganGajiFields = [
            // { name: 'Nama', value: 'asisten_guru' },
            {
                name: "Asisten Guru",
                value: "asisten_guru_id",
                type: "autocomplete",
                url: 'asisten_guru/search/autocomplete',
                valueName: 'asisten_guru_data',
                enableCreation: true
            },
            { name: 'Tanggal', value: 'tanggal', type: 'date' },
            { name: 'Nominal', value: 'nominal', type: 'number' }
        ];
        vm.additional.detailFields = [
            [
                { name: "Jenis Kelamin", value: "orang.jenis_kelamin", type: 'selection', selection: jenisKelamin, required: true },
                { name: "Agama", value: "orang.agama_id", type: 'selection', selection: agamaOptions, required: true },
                { name: "No. HP", value: "orang.no_hp", table: true, required: true },
                { name: "Email", value: "orang.email", required: true },
                { name: "Alamat", value: "orang.alamat", required: true }
            ],
            [
                { name: "Tempat Lahir", value: "orang.tempat_lahir", required: true },
                { name: "Tanggal Lahir", value: "orang.tanggal_lahir", type: 'date', required: true },
                { name: "Hobi", value: "orang.hobi", required: true }
            ]
        ];
        vm.additional.bankFields = [
            [
                { name: "Nama Bank", value: "nama_bank", required: true },
                { name: "Nomor Rekening", value: "no_rek", required: true }
            ]
        ];
        vm.additional.kursusFields = [
            { name: "Kode", value: "kode" },
            { name: "Nama", value: "nama" }
        ];
        vm.additional.surveyFields = [
            { name: "Mengapa Anda memilih berhenti dari perusahaan sebelumnya?", value: "berhenti", type: "textarea", required: true },
            { name: "Mengapa Anda memilih Students of One Family Learning Centre?", value: "memilih", type: "textarea", required: true },
            { name: "Deskripsikan secara detail, apa saja kelebihan Anda? Bagaimana cara meningkatkan kelebihan tersebut?", value: "kelebihan", type: "textarea", required: true },
            { name: "Deskripsikan secara detail, apa saja kekurangan Anda? Bagaimana cara meningkatkan kekurangan tersebut?", value: "kekurangan", type: "textarea", required: true },
            { name: "Silahkan deskripsikan kondisi Kesehatan Anda?", value: "kesehatan", type: "textarea", required: true },
            { name: "Silahkan deskripsikan lingkungan kerja yang Anda inginkan! Mengapa?", value: "lingkungan", type: "textarea", required: true },
            { name: "Jika Anda diterima, apakah Anda dapat mengikuti aturan yang diatur oleh SOOF? Mengapa?", value: "aturan", type: "textarea", required: true },
            { name: "Jika Anda diterima, apakah Anda bersedia mengikuti sistem pelatihan yang diatur oleh SOOF? Mengapa?", value: "pelatihan", type: "textarea", required: true },
            { name: "Jika Anda diterima, kapan rencana Anda mulai mengajar di SOOF?", value: "kapan", type: "textarea", required: true },
            { name: "Silahkan tuliskan gaji Anda sebelumnya", value: "gaji_sebelumnya", type: "number", required: true },
            { name: "Gaji yang Anda  minta", value: "gaji_diminta", type: "number", required: true },
            { name: "Pengenalan diri", value: "rekaman", type: "file", required: true },
            { name: "Menurut Anda, bagaimana baru dapat dikategorikan sebagai guru yang ideal?", value: "ideal", type: "textarea", required: true },
        ];
        vm.jadwalFields = [
            { name: "Hari", value: "hari", type: "selection", selection: hariOpt },
            { name: 'Waktu', value: 'waktu', type: 'time' }
        ];

        vm.getValue = getValue;
        vm.getLabel = getLabel;

        vm.generateGaji = generateGaji;
        vm.genGaji = genGaji;

        vm.slipGaji = slipGaji;
        vm.generateSlipGaji = generateSlipGaji;

        vm.tambahTunjangan = tambahTunjangan;
        vm.addTunjangan = addTunjangan;
        vm.deleteTunjangan = deleteTunjangan;

        vm.tambahPotonganGaji = tambahPotonganGaji;
        vm.addPotonganGaji = addPotonganGaji;
        vm.deletePotonganGaji = deletePotonganGaji;

        vm.liatGuru = liatGuru;
        vm.previewGuru = previewGuru;

        vm.nonaktif = nonaktif;
        vm.aktif = aktif;

        vm.editKursus = editKursus;
        vm.fetchKursus = fetchKursus;
        vm.isAdded = isAdded;
        vm.addKursus = addKursus;
        vm.removeKursus = removeKursus;

        vm.fetchSiswa = fetchSiswa;

        scope.$watch(() => vm.modal.asisten_guru_id, watchAsistenGuru);

        function watchAsistenGuru() {
            let el = $('[name="vm.modal.asisten_guru_id"]');

            if (el.length > 0) {
                if (!isNaN(vm.modal.asisten_guru_id)) {
                    let el_data = el.select2('data');

                    if (el_data.length > 0) {
                        vm.modal.asisten_guru = el_data[0].text;
                        vm.modal.nominal = el_data[0].gaji_tetap;
                        $('[name="vm.modal.nominal"]').prop('readonly', true);
                    }
                    else {
                        $('[name="vm.modal.nominal"]').prop('readonly', false);
                        vm.modal.nominal = null;
                    }
                }
                else {
                    $('[name="vm.modal.nominal"]').prop('readonly', false);
                    vm.modal.nominal = null;
                }
            }
        }

        function getValue(field) {
            let result = $parse(field.value)(vm.data);

            if (field.type == 'selection') {
                result = getLabel(result, field.selection);
            }

            return result || "-";
        }
        function getLabel(val, arr) {
            for (let index = 0; index < arr.length; index++) {
                if (arr[index].value == val) {
                    return arr[index].label;
                }
            }
        }

        function generateGaji() {
            vm.modal.tanggal = moment(new Date()).format("YYYY-MM-DD");
            vm.modalElement = $compile(modalGaji)(scope);
        }
        function genGaji() {
            if (!vm.modal.tanggal) {
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

        function slipGaji() {
            vm.modal.tanggal = moment(new Date()).format("YYYY-MM-DD");
            vm.modalElement = $compile(modalSlipGaji)(scope);
        }
        function generateSlipGaji() {
            if (!vm.modal.tanggal) {
                logger.error("Date cannot be empty");
                return;
            }

            let data = {
                start_date: vm.modal.tanggal,
                guru_id: vm.dataId
            };

            req.post('guru/generate/slip/gaji', data).then(response => {
                vm.modalElement = $compile(previewSlipGaji)(scope);
                vm.myPdf = false;
                let container = vm.modalElement[0].querySelector("#myPdf");
                container.setAttribute('src', `data:application/pdf;base64, ${response.data}#page=1&zoom=80`);
            });
        }


        function tambahTunjangan() {
            vm.modalElement = $compile(tunjangan)(scope);
        }
        function addTunjangan() {
            let data = {
                guru_id: vm.dataId,
                nama: vm.modal.nama,
                jumlah: vm.modal.jumlah,
                nominal: vm.modal.nominal
            };

            req.post('tunjangan_guru', data).then(response => {
                Modal.getInstance(vm.modalElement[0]).hide();
                state.reload();
            });
        }
        function deleteTunjangan(tunjangan) {
            let data = {
                id: tunjangan.id
            }
            req.del('tunjangan_guru', data).then(response => {
                state.reload();
            });
        }

        function tambahPotonganGaji() {
            vm.modalElement = $compile(potongan_gaji)(scope);
        }
        function addPotonganGaji() {
            let data = {
                guru_id: vm.dataId,
                asisten_guru_id: vm.modal.asisten_guru_id,
                tanggal: vm.modal.tanggal,
                nominal: vm.modal.nominal
            };

            if (isNaN(data.asisten_guru_id)) {
                data.asisten_guru = vm.modal.asisten_guru_id;
                data.asisten_guru_id = null;
            }
            else {
                data.asisten_guru = vm.modal.asisten_guru;
            }

            req.post('potongan_gaji', data).then(response => {
                Modal.getInstance(vm.modalElement[0]).hide();
                state.reload();
            });
        }
        function deletePotonganGaji(potongan_gaji) {
            let data = {
                id: potongan_gaji.id
            }
            req.del('potongan_gaji', data).then(response => {
                state.reload();
            });
        }

        function liatGuru() {
            vm.modalElement = $compile(pilihHari)(scope);
        }

        function previewGuru() {
            let url = `guru/available?hari=${vm.modal.hari}&waktu=${vm.modal.waktu}`;
            req.get(url).then(data => {
                Modal.getInstance(vm.modalElement[0]).hide();
                vm.modal.listGuru = data;
                vm.modalElement = $compile(showGuru)(scope);
            });
        }

        function nonaktif() {
            let data = vm.data;
            data['status'] = 'n';
            req.put('guru', data).then(response => {
                state.reload();
            });
        }

        function aktif() {
            let data = vm.data;
            data['status'] = 'a';
            req.put('guru', data).then(response => {
                state.reload();
            });

            // req.put('guru', data).then(response => {
            //     state.reload();
            // });
        }

        function editKursus() {
            vm.modalElement = $compile(kursusHtml)(scope);
        }

        function fetchKursus() {
            req.get('kursuss').then(response => {
                vm.modal.kursus = response.data;
            });
        }
        function isAdded(kursus) {
            let result = false;
            vm.data.kursus.forEach(value => {
                if (value.id == kursus.id) {
                    result = true;
                }
            });

            return result;
        }
        function addKursus(kursus) {
            vm.data.kursus.push(kursus);
        }
        function removeKursus(idx) {
            vm.data.kursus.splice(idx, 1);
        }

        function fetchSiswa() {
            let filter = "";
            vm.data.siswa = [];

            if (vm.filterSiswa != "") {
                filter = "?status=" + vm.filterSiswa;
            }

            req.get(`guru/data/siswa/${vm.dataId}${filter}`).then(response => {
                vm.customData.siswa = response;
            })
        }
    }
})()