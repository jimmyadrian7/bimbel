(() => {
    "use strict";

    angular.module('app.module.pengeluaran.aset')
        .controller('AsetController', AsetController);

    AsetController.$inject = ['$stateParams'];

    function AsetController(stateParams)
    {
        let vm = this;

        vm.dataId = stateParams.dataId;
        vm.fields = [
            {name: "Nama", value: "nama", table: true},
            {name: "Tanggal Beli", value: "tanggal_beli", type: 'date', table: true},
            {name: "Kondisi", value: "kondisi", table: true},
            {name: "Jumlah", value: "jumlah", type: 'number', table: true},
            {name: "Harga", value: "harga", type: 'number', table: true}
        ];
    }
})()