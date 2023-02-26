import modalGaji from "./html/modal/modal-gaji.html";
import tunjangan from "./html/modal/tunjangan.html";
import pilihHari from "./html/modal/pilih-hari.html";
import showGuru from "./html/modal/show-guru.html";
import kursusHtml from "./html/modal/kursus.html";

(() => {
    "use strict";

    angular.module('app.module.guru')
        .controller('GuruController', GuruController);

    GuruController.$inject = [
        '$stateParams', 'agamaOptions', '$compile', '$scope', 'req', '$state', 
        '$parse', 'Modal', 'logger', 'moment', 'session'
    ];

    function GuruController(
        stateParams, agamaOptions, $compile, scope, 
        req, state, $parse, Modal, logger, moment, session
    )
    {
        let vm = this;
        let jenisKelamin = [
            {label: "Laki laki", value: "l"},
            {label: "Perempuan", value: "p"}
        ];
        let hariOpt = [
            {value: "1", label: "Senin"},
            {value: "2", label: "Selasa"},
            {value: "3", label: "Rabu"},
            {value: "4", label: "Kamis"},
            {value: "5", label: "Jumat"},
            {value: "6", label: "Sabtu"},
            {value: "7", label: "Minggu"}
        ];

        vm.modal = {siswa: {}, added: [], listGuru: [], kursus: []};
        vm.siswa = [];
        vm.modalElement = false;
        vm.data = {kursus: []};
        vm.dataId = stateParams.dataId;
        vm.isSuperUser = session.isSuperUser();

        vm.statusOpt = [
            {value: 'a', label: 'Aktif'},
            {value: 'n', label: 'Nonaktif'}
        ];
        vm.fields = [
            {name: "Nama", value: "orang.nama", table: true, hideDetail: true},
            {name: "Jenis Kelamin", value: "orang.jenis_kelamin", type: 'selection', selection: jenisKelamin, table: true, hidden: true, hideDetail: true},
            {name: "No. HP", value: "orang.no_hp", table: true, hidden: true, hideDetail: true},
            {name: "Status", value: "status", type: 'selection', selection: vm.statusOpt, table: true, hidden: true, hideDetail: true},
            {name: "Profile Picture", value: "pp", type: "file", hideDetail: true}
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
        vm.additional.bankFields = [
            [
                {name: "Nama Bank", value: "nama_bank"},
                {name: "Nomor Rekening", value: "no_rek"}
            ]
        ];
        vm.additional.kursusFields = [
            {name: "Kode", value: "kode"},
            {name: "Nama", value: "nama"}
        ];
        vm.additional.surveyFields = [
            {name: "Mengapa Anda memilih berhenti dari perusahaan sebelumnya?", value: "berhenti", type: "textarea"},
            {name: "Mengapa Anda memilih Students of One Family Learning Centre?", value: "memilih", type: "textarea"},
            {name: "Deskripsikan secara detail, apa saja kelebihan Anda? Bagaimana cara meningkatkan kelebihan tersebut?", value: "kelebihan", type: "textarea"},
            {name: "Deskripsikan secara detail, apa saja kekurangan Anda? Bagaimana cara meningkatkan kekurangan tersebut?", value: "kekurangan", type: "textarea"},
            {name: "Silahkan deskripsikan kondisi Kesehatan Anda?", value: "kesehatan", type: "textarea"},
            {name: "Silahkan deskripsikan lingkungan kerja yang Anda inginkan! Mengapa?", value: "lingkungan", type: "textarea"},
            {name: "Jika Anda diterima, apakah Anda dapat mengikuti aturan yang diatur oleh SOOF? Mengapa?", value: "aturan", type: "textarea"},
            {name: "Jika Anda diterima, apakah Anda bersedia mengikuti sistem pelatihan yang diatur oleh SOOF? Mengapa?", value: "pelatihan", type: "textarea"},
            {name: "Jika Anda diterima, kapan rencana Anda mulai mengajar di SOOF?", value: "kapan", type: "textarea"},
            {name: "Silahkan tuliskan gaji Anda sebelumnya", value: "gaji_sebelumnya", type: "number"},
            {name: "Gaji yang Anda  minta", value: "gaji_diminta", type: "number"},
            {name: "Pengenalan diri", value: "rekaman", type: "file"},
            {name: "Menurut Anda, bagaimana baru dapat dikategorikan sebagai guru yang ideal?", value: "ideal", type: "textarea"},
        ];
        vm.jadwalFields = [
            {name: "Hari", value: "hari", type: "selection", selection: hariOpt}
        ];

        vm.getValue = getValue;
        vm.getLabel = getLabel;

        vm.generateGaji = generateGaji;
        vm.genGaji = genGaji;

        vm.tambahTunjangan = tambahTunjangan;
        vm.addTunjangan = addTunjangan;
        vm.deleteTunjangan = deleteTunjangan;

        vm.liatGuru = liatGuru;
        vm.previewGuru = previewGuru;

        vm.nonaktif = nonaktif;
        vm.aktif = aktif;

        vm.editKursus = editKursus;
        vm.fetchKursus = fetchKursus;
        vm.isAdded = isAdded;
        vm.addKursus = addKursus;
        vm.removeKursus = removeKursus;

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

        function liatGuru()
        {
            vm.modalElement = $compile(pilihHari)(scope);
        }

        function previewGuru()
        {
            let url = `guru/available/${vm.modal.hari}`;
            req.get(url).then(data => {
                Modal.getInstance(vm.modalElement[0]).hide();
                vm.modal.listGuru = data;
                vm.modalElement = $compile(showGuru)(scope);
            });
        }

        function nonaktif()
        {
            let data = {
                id: vm.dataId,
                status: 'n'
            };
            req.put('guru', data).then(response => {
                state.reload();
            });
        }

        function aktif()
        {
            let data = {
                id: vm.dataId,
                status: 'a'
            };
            req.put('guru', data).then(response => {
                state.reload();
            });
        }

        function editKursus()
        {
            vm.modalElement = $compile(kursusHtml)(scope);
        }

        function fetchKursus()
        {
            req.get('kursuss').then(response => {
                vm.modal.kursus = response.data;
            });
        }
        function isAdded(kursus)
        {
            let result = false;
            vm.data.kursus.forEach(value => {
                if (value.id == kursus.id)
                {
                    result = true;
                }
            });

            return result;
        }
        function addKursus(kursus)
        {
            vm.data.kursus.push(kursus);
        }
        function removeKursus(idx)
        {
            vm.data.kursus.splice(idx, 1);
        }
    }
})()