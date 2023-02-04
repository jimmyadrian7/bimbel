(() => {
    "use strict";

    angular.module('app.module.broadcast')
        .controller('BroadcastController', BroadcastController);

    BroadcastController.$inject = ['$stateParams', '$state', '$scope', 'req'];

    function BroadcastController( stateParams, state, scope, req)
    {
        let vm = this;
        let statusOption = [
            {value: "n", label: "Baru"},
            {value: "s", label: "Terkirim"}
        ];

        vm.data = {siswa: []};
        vm.additional = {};
        vm.dataId = stateParams.dataId;

        vm.fields = [
            {name: "Nama Template", value: "template_name", table: true, hidden: true},
            {name: "Status", value: "status", type: 'selection', selection: statusOption, table: true, hidden: true, hideDetail: true}
        ];

        vm.additional.siswaFields = [
            {name: 'No. Formulir', value: 'no_formulir'},
            {name: 'Tanggal Pendaftaran', value: 'tanggal_pendaftaran', type: 'date'},
            {name: 'Nama', value: 'orang.nama'}
        ];


        vm.cancelEdit = cancelEdit;
        vm.getLabel = getLabel;
        vm.sendMessage = sendMessage;

        scope.$watch(() => vm.data.template_name, setContent);

        function cancelEdit()
        {
            state.go('broadcast');
        }

        function setContent()
        {
            let el = $('#template_name');
            
            if (el.length > 0)
            {
                let el_data = el.select2('data');

                if (el_data.length > 0)
                {
                    vm.data.content = el_data[0].content;
                }
            }
        }

        function getLabel(val)
        {
            for (let index = 0; index < statusOption.length; index++) {
                if (statusOption[index].value == val)
                {
                    return statusOption[index].label;
                }
            }
        }

        function sendMessage()
        {
            let data = {
                id: vm.dataId,
                status: 's'
            };
            req.put('broadcast', data).then(response => state.reload());
        }
    }
})()