(() => {
    "use strict";

    angular.module('app.module.pembayaran.pembiayaan')
        .controller('PembiayaanController', PembiayaanController);

    PembiayaanController.$inject = ['$stateParams'];

    function PembiayaanController(stateParams)
    {
        let vm = this;

        let kategoriOpt = [
            {value: 'a', label: 'Aksesoris'},
            {value: 's', label: 'SPP'},
            {value: 'p', label: 'Pendaftaran'},
            {value: 'd', label: 'Deposit'},
            {value: 'l', label: 'Lain-lain'}
        ];

        vm.dataId = stateParams.dataId;
        vm.fields = [
            {name: "Kategori", value: "kategori_pembiayaan", type: "selection", selection: kategoriOpt, table: true},
            {name: "kode", value: "kode", table: true},
            {name: "nama", value: "nama", table: true},
            {name: "Harga", value: "harga", type: 'number', table: true},
            {name: "Stok", value: "stok", type: 'boolean'},
            {name: "Komisi", value: "komisi", type: 'boolean'},
            {name: "Jumlah Stok", value: "jumlah_stok", type: 'number'},
        ];
    }
})()