(() => {
    "use strict";

    angular.module('app.module.message')
        .controller('MessageController', MessageController);

    MessageController.$inject = ['$stateParams'];

    function MessageController(stateParams)
    {
        let vm = this;

        vm.data = {};
        vm.dataId = stateParams.dataId;

        vm.fields = [
            {
                name: "Siswa",
                value: "siswa_id",
                type: "autocomplete",
                url: 'siswa/search/autocomplete',
                valueName: 'siswa_data',
                table: true, 
            },
            {name: "Message", value: "message", type: "textarea", table: true}
        ];
    }
})()