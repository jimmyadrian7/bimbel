(() => {
    "use strict";

    angular.module('app.module.konfigurasi.fix_data')
        .controller('FixDataController', FixDataController);

    FixDataController.$inject = ['$stateParams', '$compile', '$scope', 'req', 'logger'];

    function FixDataController(stateParams, $compile, $scope, req, logger) {
        let vm = this;

        vm.resetKodeTagihan = resetKodeTagihan;
        vm.fixDataTagihan = fixDataTagihan;
        vm.addFieldKeluarDeposit = addFieldKeluarDeposit;
        vm.addFieldSequancePendaftaranKursus = addFieldSequancePendaftaranKursus;
        vm.updateNoFormulirSiswa = updateNoFormulirSiswa;
        vm.addFieldYouTubeWeb = addFieldYouTubeWeb;
        vm.addFieldPhoneWeb = addFieldPhoneWeb;
        vm.addMenuIuran = addMenuIuran;
        vm.addTableRiwayatPenarikan = addTableRiwayatPenarikan;
        vm.fixData = fixData;
        vm.patch = patch;

        function resetKodeTagihan() {
            req.post('reset/sequance/tagihan').then(resp => {
                logger.success("Berhasil reset sequance Tagihan");
            });
        }

        function fixDataTagihan() {
            req.post('fix/data/tagihan').then(resp => {
                logger.success("Berhasil perbaiki data Tagihan");
            });
        }

        function addFieldKeluarDeposit() {
            req.post('add/field/keluar/deposit').then(resp => {
                logger.success("Berhasil ditambahkan");
            });
        }

        function addFieldSequancePendaftaranKursus() {
            req.post('add/field/sequance-pendaftaran/kursus').then(resp => {
                logger.success("Berhasil ditambahkan");
            });
        }

        function updateNoFormulirSiswa() {
            req.post('update/no/formulir/siswa').then(resp => {
                logger.success("Berhasil di update");
            });
        }

        function addFieldYouTubeWeb() {
            req.post('add/field/youtube/web').then(resp => {
                logger.success("Berhasil di tambah");
            });
        }

        function addFieldPhoneWeb() {
            req.post('add/field/phone/web').then(resp => {
                logger.success("Berhasil di tambah");
            });
        }

        function addMenuIuran() {
            req.post('add/menu/iuran').then(resp => {
                logger.success("Berhasil di tambah");
            });
        }

        function addTableRiwayatPenarikan() {
            req.post('add/table/riwayat/penarikan').then(resp => {
                logger.success("Berhasil di tambah");
            });
        }

        function fixData(url) {
            req.post('fix/data/' + url).then(resp => {
                logger.success("Berhasil di perbaiki");
            });
        }

        function patch(version) {
            req.post(`patch/${version}`).then(resp => {
                logger.success("Berhasil di patch");
            });
        }
    }
})()