(() => {
    "use strict";

    angular.module('app.module.pengeluaran.pengeluaran')
        .controller('PengeluaranController', PengeluaranController);

    PengeluaranController.$inject = ['$stateParams'];

    function PengeluaranController(stateParams)
    {
        let vm = this;

        vm.dataId = stateParams.dataId;
        vm.fields = [
            {name: "Nama", value: "nama", table: true},
            {name: "Jumlah", value: "jumlah", type: 'number', table: true},
            {name: "Harga", value: "harga", type: 'number', table: true},
            {name: "Total", value: "total", type: 'number', table: true, hidden: true},
            {name: "Tanggal", value: "tanggal", type: 'date', table: true}
        ];
    }
})()