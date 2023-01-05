import iuran from "./html/modal/iuran.html";
import edit_iuran from "./html/modal/edit_iuran.html";
import jadwal from "./html/modal/jadwal.html";

(() => {
    "use strict";

    angular.module('app.module.siswa.siswa')
        .controller('SiswaController', SiswaController);

    SiswaController.$inject = [
        '$stateParams', 'agamaOptions', '$parse', 'req', '$state', '$compile', '$scope', 'Modal', 'session',
        'referalOptions', 'kursusOptions'
    ];

    function SiswaController(
        stateParams, agamaOptions, $parse, req, state, $compile, $scope, Modal, session, 
        referalOptions, kursusOptions
    )
    {
        let vm = this;
        let jenisKelamin = [
            {label: "Laki laki", value: "l"},
            {label: "Perempuan", value: "p"}
        ];
        let statusOpt = [
            {label: "Baru", value: "b"},
            {label: "Aktif", value: "a"},
            {label: "Pengembalian", value: "p"},
            {label: "Berhenti", value: "n"}
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

        vm.myModal = false;
        vm.data = {iuran: [], jadwal: [], ref: {}};
        vm.modal = {};
        vm.dataId = stateParams.dataId;
        vm.activeIndex = -1;
        vm.isSuperUser = session.isSuperUser();
        vm.referalOptions = referalOptions;
        vm.edit_form = vm.isSuperUser ? 'siswa.siswa_form' : '';

        vm.status_field = {name: "Status", value: "status", type: "selection", selection: statusOpt, table: true, hidden: true, hideDetail: true};
        vm.fields = [
            {name: "No. Formulir", value: "no_formulir", table: true, hidden: true},
            {name: "Tanggal Pendaftaran", value: "tanggal_pendaftaran", table: true, hidden: true, type: "date"},
            {
                name: "Guru", 
                value: "guru_id", 
                type: "autocomplete",
                url: 'guru/search/autocomplete',
                valueName: 'guru_data',
                table: true,
                hidden: !vm.isSuperUser
            },
            {name: "Nama", value: "orang.nama", table: true},
            {name: "Nama Mandarin", value: "orang.nama_mandarin", table: true},
            {name: "Komisi", value: "komisi", type: 'number', table: true},
            {name: "Program", value: "program"},
            {name: "Paket Belajar", value: "paket_belajar"},
            {name: "Tempat Kursus", value: "kursus_id", table: true, type: "selection", selection: kursusOptions},
            vm.status_field
        ];

        vm.additional = {};
        vm.additional.detailFields = [
            [
                {name: "Jenis Kelamin", value: "orang.jenis_kelamin", type: 'selection', selection: jenisKelamin},
                {name: "Agama", value: "orang.agama_id", type: 'selection', selection: agamaOptions},
                {name: "Tempat Lahir", value: "orang.tempat_lahir"},
                {name: "Tanggal Lahir", value: "orang.tanggal_lahir", type: 'date'},
            ],
            [
                {name: "No. HP", value: "orang.no_hp", table: true},
                {name: "Email", value: "orang.email"},
                {name: "Alamat", value: "orang.alamat"},
                {name: "Sekolah", value: "sekolah"},
                {name: "Kelas", value: "kelas"}
            ]
        ];
        vm.additional.ortuFields = [
            [
                {name: "Nama Ayah", value: "orang.nama_ayah"},
                {name: "Nama Ibu", value: "orang.nama_ibu"},
                {name: "No. HP Orang Tua", value: "orang.no_hp_ortu"}
            ],
            [
                {name: "Pekerjaan Ayah", value: "orang.pekerjaan_ayah"},
                {name: "Pekerjaan Ibu", value: "orang.pekerjaan_ibu"}
            ]
        ];
        vm.additional.testFields = [
            [
                {name: "Pinyin", value: "pinyin"},
                {name: "Dengar", value: "dengar"},
                {name: "Bicara", value: "bicara"}
            ],
            [
                {name: "Membaca", value: "membaca"},
                {name: "Menulis", value: "menulis"},
                {name: "Kondisi Siswa", value: "kondisi"}
            ]
        ];
        vm.additional.responFields = [
            [
                {name: "Respon & Tanggapan", value: "respon"},
                {name: "Tanggapan guru", value: "tanggapan"}
            ]
        ];
        vm.iuranFields = [
            {name: "Nama", value: "nama"},
            {name: "Bulan", value: "bulan", type: 'number'}
        ];
        vm.jadwalFields = [
            {name: 'Hari', value: 'hari', type: 'selection', selection: hariOpt},
            {name: 'Waktu', value: 'waktu', type: 'time'},
        ];

        vm.getValue = getValue;
        vm.getHari = getHari;
        vm.updateStatus = updateStatus;

        vm.tambahIuran = tambahIuran;
        vm.fetchIuran = fetchIuran;
        vm.isAdded = isAdded;
        vm.addIuran = addIuran;
        vm.removeIuran = removeIuran;

        vm.editIuran = editIuran;
        vm.modifyIuran = modifyIuran;

        vm.formJadwal = formJadwal;
        vm.addJadwal = addJadwal;
        vm.removeJadwal = removeJadwal;

        activate();

        function activate()
        {
            if (vm.data.guru_id = session.getSession().guru)
            {
                let guru = session.getSession().guru;
                vm.data.guru_data = {id: guru.id, nama: guru.orang.nama};
                let getter = $parse('guru_id');
                getter.assign(vm.data, vm.data.guru_data);

                if (!session.isSuperUser())
                {
                    vm.data.guru_id = guru.id;
                }
            }
        }

        function getValue(field)
        {
            if (!field)
            {
                return "-";
            }

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
        function getHari(data)
        {
            for (let index = 0; index < hariOpt.length; index++) {
                if (hariOpt[index].value == data.hari)
                {
                    return hariOpt[index].label;
                }
            }
        }

        function updateStatus()
        {
            let data = {
                id: vm.data.id,
                status: 'p'
            };

            req.put('siswa', data).then(response => state.reload());
        }

        function tambahIuran()
        {
            vm.myModal = $compile(iuran)($scope);
        }
        function fetchIuran()
        {
            req.get('iurans').then(data => {
                vm.modal.iuran = data;
            });
        }
        function isAdded(iuran)
        {
            let result = false;

            for (let index = 0; index < vm.data.iuran.length; index++) {
                const s = vm.data.iuran[index];
                
                if (s.id == iuran.id && s.action != 'delete')
                {
                    result = true;
                    break;
                }
            }

            return result;
        }
        function addIuran(iuran)
        {
            let isExist = false;
            for (let index = 0; index < vm.data.iuran.length; index++) {
                const s = vm.data.iuran[index];
                
                if (s.id == iuran.id)
                {
                    delete vm.data.iuran[index].action;
                    isExist = true;
                    break;
                }
            }
            
            if (!isExist)
            {
                let data = angular.copy(iuran);
                data.action = 'add';
                vm.data.iuran.push(data);
            }
        }
        function removeIuran(idx)
        {
            if (!vm.data.iuran[idx].action)
            {
                vm.data.iuran[idx].action = 'delete';
            }
        }

        function editIuran(idx)
        {
            vm.activeIndex = idx;
            vm.myModal = $compile(edit_iuran)($scope);
        }
        function modifyIuran(iuran)
        {
            let current_iuran = vm.data.iuran[vm.activeIndex];
            vm.data.iuran[vm.activeIndex].old_id = current_iuran.id;
            vm.data.iuran[vm.activeIndex].id = iuran.id;
            vm.data.iuran[vm.activeIndex].action = "edit";

            let data = {
                id: vm.data.id,
                iuran: vm.data.iuran
            };

            req.put('siswa', data).then(response => {
                vm.activeIndex = -1;
                Modal.getInstance(vm.myModal[0]).hide();
                state.reload();
            });
        }

        function formJadwal(idx)
        {
            let data = {};
            vm.modal.action = "add";

            if (idx !== undefined)
            {
                vm.modal.activeIdx = idx;
                vm.modal.action = "edit";
                data = vm.data.jadwal[idx];
            }

            vm.modal.hari = data.hari;
            vm.modal.waktu = data.waktu;

            vm.myModal = $compile(jadwal)($scope);
        }
        function addJadwal()
        {
            if (vm.modal.action == 'add')
            {
                vm.data.jadwal.push({
                    hari: vm.modal.hari,
                    waktu: vm.modal.waktu
                });
            }
            else
            {
                let idx = vm.modal.activeIdx;
                vm.data.jadwal[idx].hari = vm.modal.hari;
                vm.data.jadwal[idx].waktu = vm.modal.waktu;
            }

            Modal.getInstance(vm.myModal[0]).hide();
        }
        function removeJadwal(idx)
        {
            vm.data.jadwal.splice(idx, 1);
        }
    }
})()