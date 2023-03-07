(() => {
    "use strict";

    angular.module('app.module.web.pengumuman')
        .controller('PengumumanController', PengumumanController);

    PengumumanController.$inject = ['$stateParams'];

    function PengumumanController(stateParams)
    {
        let vm = this;

        vm.dataId = stateParams.dataId;
        vm.fields = [
            {name: "Judul", value: "judul", table: true, required: true},
            {name: "Isi", value: "isi", type: 'textarea', required: true},
            {name: "Gambar", value: "gambar", type: "file", table: true, required: true},
            {name: "Tanggal", value: "tanggal", type: "date", table: true, required: true}
        ];
    }
})()