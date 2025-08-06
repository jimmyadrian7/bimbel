(() => {
    "use strict";

    angular.module('app.module.konfigurasi.report_profile')
        .controller('ReportProfileController', ReportProfileController);

    ReportProfileController.$inject = ['$stateParams'];

    function ReportProfileController(stateParams) {
        let vm = this;

        vm.dataId = stateParams.dataId;
        vm.fields = [
            { name: "Logo", value: "logo", type: 'file' },
            { name: "Alamat", value: "alamat", type: 'textarea' },
            { name: "No. HP", value: "no_hp" },
            { name: "Email", value: "email" },
        ];
    }
})()