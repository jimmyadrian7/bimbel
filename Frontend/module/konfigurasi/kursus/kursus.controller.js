(() => {
    "use strict";

    angular.module('app.module.konfigurasi.kursus')
        .controller('KursusController', KursusController);

    KursusController.$inject = ['$stateParams'];

    function KursusController(stateParams) {
        let vm = this;

        vm.dataId = stateParams.dataId;
        vm.fields = [
            { name: "Kode", value: "kode", table: true, required: true },
            { name: "Nama", value: "nama", table: true, required: true },
            { name: "Sequance", value: "sequance", table: true, required: true, hidden: true },
            { name: "No. Rekening", value: "no_rek", table: false },
            { name: "Nama Penerima", value: "nama_rek", table: false },
            { name: "Logo Bank", value: "logo_bank", type: "file" }
        ];
    }
})()