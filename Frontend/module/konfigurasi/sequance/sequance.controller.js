(() => {
    "use strict";

    angular.module('app.module.konfigurasi.sequance')
        .controller('SequanceController', SequanceController);

    SequanceController.$inject = ['$stateParams'];

    function SequanceController(stateParams)
    {
        let vm = this;

        vm.dataId = stateParams.dataId;
        vm.fields = [
            {name: "Kode", value: "kode", table: true},
            {name: "Nama", value: "nama", table: true},
            {name: "Nomor", value: "nomor", table: true}
        ];
    }
})()