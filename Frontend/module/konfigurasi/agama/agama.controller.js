(() => {
    "use strict";

    angular.module('app.module.konfigurasi.agama')
        .controller('AgamaController', AgamaController);

    AgamaController.$inject = ['$stateParams'];

    function AgamaController(stateParams)
    {
        let vm = this;

        vm.dataId = stateParams.dataId;
        vm.fields = [
            {name: "Kode", value: "kode", table: true, required: true},
            {name: "Nama", value: "nama", table: true, required: true}
        ];
    }
})()