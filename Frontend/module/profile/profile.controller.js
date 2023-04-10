import modal from "../konfigurasi/user/html/modal.html";

(() => {
    "use strict";

    angular.module('app.module.profile')
        .controller('ProfileController', ProfileController);

    ProfileController.$inject = ['agamaOptions', 'session', '$state', '$parse', 'req', 'Modal', '$compile', '$scope', 'logger'];

    function ProfileController(agamaOptions, session, state, $parse, req, Modal, $compile, $scope, logger)
    {
        let vm = this;
        let jenisKelamin = [
            {label: "Laki laki", value: "l"},
            {label: "Perempuan", value: "p"}
        ];

        vm.data = session.getSession().orang;
        vm.form = {};
        vm.pengumuman = [];
        vm.myModal = false;

        vm.isGuru = session.isGuru();
        vm.isSiswa = session.isSiswa();
        vm.guru = {tunjangan: []};
        vm.siswa = {tunjangan: []};
        vm.editable = session.getMenu(state.current.name).update;

        vm.fields = [
            [
                {name: "Nama", value: "nama", required: true},
                {name: "Jenis Kelamin", value: "jenis_kelamin", type: 'selection', selection: jenisKelamin, required: true},
                {name: "Agama", value: "agama_id", type: 'selection', selection: agamaOptions, required: true},
                {name: "Tempat Lahir", value: "tempat_lahir", required: true},
                {name: "Tanggal Lahir", value: "tanggal_lahir", type: 'date', required: true},
                {name: "Profile Picture", value: "pp", type: "file", hideDetail: true, required: true}
            ],
            [
                {name: "No. HP", value: "no_hp", required: true},
                {name: "Email", value: "email", required: true},
                {name: "Alamat", value: "alamat", required: true},
                {name: "Hobi", value: "hobi"}
            ]
        ];
        vm.tunjanganFields = [
            {name: 'Nama', value: 'nama'},
            {name: 'Nominal', value: 'nominal', type: 'number'}
        ];
        vm.gajiFields = [
            {name: 'Total Siswa', value: 'total_siswa', type: 'number'},
            {name: 'Gaji', value: 'total', type: 'number'},
            {name: 'Bulan', value: 'bulan'},
            {name: 'Tahun', value: 'tahun'},
            {name: 'Tanggal Gajian', value: 'tanggal', type: 'date'}
        ];
        vm.kursusFields = [
            {name: "Kode", value: "kode"},
            {name: "Nama", value: "nama"}
        ];
        vm.ortuFields = [
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

        vm.editData = editData;
        vm.cancelEdit = cancelEdit;
        vm.saveData = saveData;
        vm.gantiPass = gantiPass;
        vm.saveUser = saveUser;
        vm.checkValidation = checkValidation;

        vm.getValue = getValue;

        vm.fetchPengumuman = fetchPengumuman;

        activate();

        function activate()
        {
            if (vm.isGuru)
            {
                vm.guru = session.getSession().guru;
            }
            if (vm.isSiswa)
            {
                vm.siswa = session.getSession().siswa;
            }
        }

        function editData()
        {
            state.go('profile_form');
        }
        function cancelEdit()
        {
            state.go('profile');
        }
        function saveData()
        {
            req.put('orang', vm.data).then(data => {
                cancelEdit();
            });
        }
        function gantiPass()
        {
            vm.myModal = $compile(modal)($scope);
        }
        function saveUser()
        {
            if (!vm.form.password)
            {
                logger.error('Password cannot be empty');
                return;
            }

            let data = {
                id: session.getSession().id,
                password: vm.form.password
            };

            req.put('user', data).then(response => {
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

            if (field.type == 'autocomplete')
            {
                result = $parse(field.valueName + ".nama")(vm.data);
            }

            if (field.type == 'file')
            {
                result = $parse(field.value + ".filename")(vm.data);
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

        function fetchPengumuman()
        {
            req.get('pengumumans').then(response => {
                vm.pengumuman = response.data;
            });
        }

        function checkValidation(isValid)
        {
            // @need to uncoment
            if (isValid)
            {
                saveData();
            }
            saveData();
        }
    }
})()