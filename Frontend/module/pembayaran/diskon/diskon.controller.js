(() => {
    "use strict";

    angular.module('app.module.pembayaran.diskon')
        .controller('DiskonController', DiskonController);

    DiskonController.$inject = ['$stateParams'];

    function DiskonController(stateParams)
    {
        let vm = this;
        let diksonOption = [
            {label: "Persen", value: "p"},
            {label: "Nominal", value: "n"}
        ];

        vm.dataId = stateParams.dataId;
        vm.fields = [
            {name: "Diskon", value: "diskon", type: 'number', table: true},
            {name: "Tipe Diskon", value: "tipe_diskon", type: 'selection', selection: diksonOption, table: true}
        ];
    }
})()