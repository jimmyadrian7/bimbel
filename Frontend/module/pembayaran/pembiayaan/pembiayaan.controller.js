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

        let jenisKomisiOpt = [
            {value: null, label: 'Tidak ada Pembagian'},
            {value: 's', label: 'Siswa'},
            {value: 'p', label: 'Persen'},
            {value: 'n', label: 'Nominal'}
        ];

        vm.dataId = stateParams.dataId;
        vm.data = {};
        vm.fields = [
            {name: "Kategori", value: "kategori_pembiayaan", type: "selection", selection: kategoriOpt, table: true, required: true},
            {name: "kode", value: "kode", table: true, required: true},
            {name: "nama", value: "nama", table: true, required: true},
            {name: "Harga", value: "harga", type: 'number', table: true, required: true},
            {name: "Stok", value: "stok", type: 'boolean'},
            {name: "Jenis Pembagian", value: "jenis_komisi", type: "selection", selection: jenisKomisiOpt, required: false}
        ];
    }
})()