(() => {
    "use strict";

    angular.module('app.module.konfigurasi.referal')
        .controller('ReferalController', ReferalController);

    ReferalController.$inject = ['$stateParams'];

    function ReferalController(stateParams)
    {
        let vm = this;

        vm.dataId = stateParams.dataId;
        vm.fields = [
            {name: "Nama", value: "nama", table: true}
        ];
    }
})()