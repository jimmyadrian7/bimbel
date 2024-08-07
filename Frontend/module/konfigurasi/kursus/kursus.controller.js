(() => {
    "use strict";

    angular.module('app.module.konfigurasi.kursus')
        .controller('KursusController', KursusController);

    KursusController.$inject = ['$stateParams'];

    function KursusController(stateParams)
    {
        let vm = this;

        vm.dataId = stateParams.dataId;
        vm.fields = [
            {name: "Kode", value: "kode", table: true, required: true},
            {name: "Nama", value: "nama", table: true, required: true},
            {name: "Sequance", value: "sequance", table: true, required: true, hidden: true},
            {name: "Sequance Pendaftaran", value: "sequance_pendaftaran", table: true, required: true, hidden: true},
        ];
    }
})()