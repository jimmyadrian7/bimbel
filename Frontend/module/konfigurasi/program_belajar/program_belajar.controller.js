(() => {
    "use strict";

    angular.module('app.module.konfigurasi.program_belajar')
        .controller('ProgramBelajarController', ProgramBelajarController);

    ProgramBelajarController.$inject = ['$stateParams'];

    function ProgramBelajarController(stateParams)
    {
        let vm = this;

        vm.dataId = stateParams.dataId;
        vm.fields = [
            { name: "Kode", value: "kode", table: true, required: true },
            { name: "Nama", value: "nama", table: true, required: true },
            { name: "Nama Mandarin", value: "nama_mandarin", table: true }
        ];
    }
})()