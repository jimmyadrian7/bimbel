import iuran from "./html/modal/iuran.html";
import edit_iuran from "./html/modal/edit_iuran.html";
import jadwal from "./html/modal/jadwal.html";
import tagihan_modal from "./html/modal/tagihan.html";
import generate_tagihan_modal from "./html/modal/generate_tagihan_modal.html";

(() => {
    "use strict";

    angular.module('app.module.siswa.siswa')
        .controller('SiswaController', SiswaController);

    SiswaController.$inject = [
        '$stateParams', 'agamaOptions', '$parse', 'req', '$state', '$compile', '$scope', 'Modal', 'session',
        'referalOptions'
    ];

    function SiswaController(
        stateParams, agamaOptions, $parse, req, state, $compile, $scope, Modal, session, 
        referalOptions
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
        vm.reset = false;
        vm.data = {iuran: [], jadwal: [], ref: {}};
        vm.modal = {};
        vm.dataId = stateParams.dataId;
        vm.activeIndex = -1;
        vm.hideGuru = session.isSuperUser() || session.isAdminCabang();
        vm.isSuperUser = session.isSuperUser() || session.isAdminCabang();
        vm.referalOptions = referalOptions;

        vm.status_field = {name: "Status", value: "status", type: "selection", selection: statusOpt, table: true, hidden: true, hideDetail: true};
        vm.fields = [
            {name: "No. Formulir", value: "no_formulir", table: true, required: true},
            {name: "Tanggal Pendaftaran", value: "tanggal_pendaftaran", table: true, type: "date", required: true},
            {
                name: "Guru", 
                value: "guru_id", 
                type: "autocomplete",
                url: 'guru/search/autocomplete',
                valueName: 'guru_data',
                table: true,
                hidden: !vm.hideGuru,
                required: true
            },
            {name: "Nama", value: "orang.nama", table: true, required: true},
            {name: "Nama Mandarin", value: "orang.nama_mandarin", table: true, required: true},
            {name: "Komisi", value: "komisi", type: 'number', table: true, required: true},
            {name: "Program", value: "program", required: true},
            {name: "Paket Belajar", value: "paket_belajar", required: true},
            // {name: "Tempat Kursus", value: "kursus_id", table: true, type: "selection", selection: kursusOptions, required: true},
            {
                name: "Tempat Kursus", 
                value: "kursus_id", 
                type: "autocomplete",
                url: 'kursus/search/autocomplete',
                valueName: 'kursus_data',
                table: true,
                required: true
            },
            vm.status_field,
            {name: "Profile Picture", value: "orang.pp", type: "file", hideDetail: true}
        ];

        vm.additional = {};
        vm.additional.detailFields = [
            [
                {name: "Jenis Kelamin", value: "orang.jenis_kelamin", type: 'selection', selection: jenisKelamin, required: true},
                {name: "Agama", value: "orang.agama_id", type: 'selection', selection: agamaOptions, required: true},
                {name: "Tempat Lahir", value: "orang.tempat_lahir", required: true},
                {name: "Tanggal Lahir", value: "orang.tanggal_lahir", type: 'date', required: true},
            ],
            [
                {name: "No. HP", value: "orang.no_hp", table: true, required: true},
                {name: "Email", value: "orang.email", required: true},
                {name: "Alamat", value: "orang.alamat", required: true},
                {name: "Sekolah", value: "sekolah", required: true},
                {name: "Kelas", value: "kelas", required: true}
            ]
        ];
        vm.additional.ortuFields = [
            [
                {name: "Nama Ayah", value: "orang.nama_ayah", required: true},
                {name: "Nama Ibu", value: "orang.nama_ibu", required: true},
                {name: "No. HP Orang Tua", value: "orang.no_hp_ortu", required: true}
            ],
            [
                {name: "Pekerjaan Ayah", value: "orang.pekerjaan_ayah", required: true},
                {name: "Pekerjaan Ibu", value: "orang.pekerjaan_ibu", required: true}
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
            {name: "Respon & Tanggapan Orang Tua", value: "respon", type: 'textarea'},
            {name: "Tanggapan Guru", value: "tanggapan", type: 'textarea'}
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
        vm.updateAktif = updateAktif;

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

        vm.getReportSiswa = getReportSiswa;
        vm.getReportJadwal = getReportJadwal;

        vm.resetTagihan = resetTagihan;
        vm.generateTagihan = generateTagihan;
        vm.buatTagihan = buatTagihan;

        vm.modalTagihan = modalTagihan;
        vm.genTagihan = genTagihan;

        activate();
        $scope.$watch(() => vm.data.kursus_id, watchTempatKursus);

        function activate()
        {
            if (vm.data.guru_id = session.getSession().guru)
            {
                let guru = session.getSession().guru;
                vm.data.guru_data = {id: guru.id, nama: guru.orang.nama};
                let getter = $parse('guru_id');
                getter.assign(vm.data, vm.data.guru_data);

                if (!vm.hideGuru)
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
            let data = vm.data;
            data['status'] = 'p';

            req.put('siswa', data).then(response => state.reload());
        }

        function updateAktif()
        {
            let data = vm.data;
            data['status'] = 'a';

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
            else
            {
                vm.data.iuran.splice(idx, 1);
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

            let data = vm.data;

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

        function getReportSiswa()
        {
            req.get(`generate/report/siswa`).then(response => {
                vm.activeExcel = {filename: "siswa.xlsx", filetype: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', base64: response.data};
                let element = `<app-download-preview value='vm.activeExcel'></app-download-preview>`;
                element = $compile(element)($scope);
            });
        }

        function getReportJadwal()
        {
            req.get(`generate/report/jadwal`).then(response => {
                vm.activeExcel = {filename: "jadwal.xlsx", filetype: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', base64: response.data};
                let element = `<app-download-preview value='vm.activeExcel'></app-download-preview>`;
                element = $compile(element)($scope);
            });
        }

        function resetTagihan()
        {
            vm.reset = true;
            vm.myModal = $compile(tagihan_modal)($scope);
        }
        function buatTagihan()
        {
            vm.reset = false;
            vm.myModal = $compile(tagihan_modal)($scope);
        }
        function generateTagihan()
        {
            let data = {
                tanggal: vm.modal.tanggal_tagihan,
                reset: vm.reset,
                id: vm.dataId
            };

            req.post('siswa/generate/tagihan', data).then(response => {
                Modal.getInstance(vm.myModal[0]).hide();
                state.reload();
            });
        }
        
        function modalTagihan()
        {
            vm.myModal = $compile(generate_tagihan_modal)($scope);
        }

        function genTagihan()
        {
            let data = {
                tanggal: vm.modal.tanggal_tagihan
            };

            req.post('siswa/mass/generate/tagihan', data).then(response => {
                Modal.getInstance(vm.myModal[0]).hide();
                state.reload();
            });
        }

        function watchTempatKursus()
        {
            let el = $('[name="vm.data.kursus_id"]');
            
            if (el.length > 0)
            {
                let el_data = el.select2('data');

                if (el_data.length > 0 && el_data[0].kode)
                {
                    vm.data.no_formulir = el_data[0].kode + "-" + String(el_data[0].sequance_pendaftaran + 1).padStart(3, '0');
                }
            }
        }
    }
})()