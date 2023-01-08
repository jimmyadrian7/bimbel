(() => {
    "use strict";

    angular.module('app.module.web.konfigurasi')
        .controller('WebKonfigurasiController', KonfigurasiController);

    KonfigurasiController.$inject = ['$stateParams'];

    function KonfigurasiController(stateParams)
    {
        let vm = this;

        vm.dataId = stateParams.dataId;
        vm.fields = [
            {name: "Lokasi", value: "lokasi"},
            {name: "Email", value: "email"},
            {name: "G-Map", value: "gmap"},
            {name: "No. HP", value: "no_hp"},
            {name: "Facebook", value: "facebook"},
            {name: "Whatsapp", value: "whatsapp"},
            {name: "Instagram", value: "instagram"},
            {name: "Tiktok", value: "tiktok"}
        ];
    }
})()