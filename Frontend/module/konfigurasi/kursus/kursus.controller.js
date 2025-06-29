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
            { name: "Logo Bank", value: "logo_bank", type: "file" },
            { name: "Diserahkan Oleh", value: "diserahkan_oleh", table: false },
            { name: "TTD Diserahkan Oleh", value: "diserahkan_oleh_file", type: 'file', table: false },
            { name: "Diketahui Oleh", value: "diketahui_oleh", table: false },
            { name: "TTD Diketahui Oleh", value: "diketahui_oleh_file", type: 'file', table: false },
            { name: "Diterima Oleh", value: "diterima_oleh", table: false },
            { name: "TTD Diterima Oleh", value: "diterima_oleh_file", type: 'file', table: false }
        ];
    }
})()