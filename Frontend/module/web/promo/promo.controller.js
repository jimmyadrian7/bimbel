(() => {
    "use strict";

    angular.module('app.module.web.promo')
        .controller('PromoController', PromoController);

    PromoController.$inject = ['$stateParams'];

    function PromoController(stateParams)
    {
        let vm = this;

        vm.dataId = stateParams.dataId;
        vm.fields = [
            {name: "Tanggal", value: "tanggal", type: "date", table: true, required: true},
            {name: "Judul", value: "judul", table: true, required: true},
            {name: "Gambar", value: "gambar", type: "file", table: true, required: true}
        ];
    }
})()