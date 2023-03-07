(() => {
    "use strict";

    angular.module('app.module.pengeluaran.aset')
        .controller('AsetController', AsetController);

    AsetController.$inject = ['$stateParams', 'kursusOptions'];

    function AsetController(stateParams, kursusOptions)
    {
        let vm = this;

        vm.dataId = stateParams.dataId;
        vm.fields = [
            {name: "Tempat Kursus", value: "kursus_id", type: 'selection', selection: kursusOptions, table: true, required: true},
            {name: "Nama", value: "nama", table: true, required: true},
            {name: "Tanggal Beli", value: "tanggal_beli", type: 'date', table: true, required: true},
            {name: "Kondisi", value: "kondisi", table: true, required: true},
            {name: "Jumlah", value: "jumlah", type: 'number', table: true, required: true},
            {name: "Harga", value: "harga", type: 'number', table: true, required: true}
        ];
    }
})()