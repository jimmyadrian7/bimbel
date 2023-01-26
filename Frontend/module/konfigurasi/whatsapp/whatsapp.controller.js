(() => {
    "use strict";

    angular.module('app.module.konfigurasi.whatsapp')
        .controller('WhatsappController', WhatsappController);

    WhatsappController.$inject = ['$stateParams'];

    function WhatsappController(stateParams)
    {
        let vm = this;

        vm.dataId = stateParams.dataId;
        vm.fields = [
            {name: "ID", value: "id", table: true},
            {name: "Nama", value: "name", table: true},
            {name: "Status", value: "status", table: true},
            {name: "Category", value: "category", table: true}
        ];
    }
})()