(() => {
    "use strict";

    angular.module('app.module.web.testimoni')
        .controller('TestimoniController', TestimoniController);

    TestimoniController.$inject = ['$stateParams'];

    function TestimoniController(stateParams)
    {
        let vm = this;
        let tipeOpt = [
            {label: "Gambar", value: "gambar"},
            {label: "Link", value: "link"}
        ];

        vm.dataId = stateParams.dataId;
        vm.fields = [
            {name: "Tipe", value: "tipe", type: "selection", selection: tipeOpt, table: true},
            {name: "Link", value: "link", table: true},
            {name: "Gambar", value: "gambar", type: "file", table: true}
        ];
    }
})()