(() => {
    "use strict";

    angular.module('app.module.konfigurasi.menu')
        .controller('MenuController', MenuController);

    MenuController.$inject = ['$stateParams'];

    function MenuController(stateParams)
    {
        let vm = this;

        vm.dataId = stateParams.dataId;
        vm.fields = [
            {name: "Kode", value: "kode", table: true},
            {name: "Nama", value: "nama", table: true}
        ];
    }
})()