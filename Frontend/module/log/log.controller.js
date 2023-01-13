(() => {
    "use strict";

    angular.module('app.module.log')
        .controller('LogController', LogController);

    LogController.$inject = ['$stateParams', 'formatHighlight', '$sce'];

    function LogController(stateParams, formatHighlight, $sce)
    {
        let vm = this;
        
        vm.data = {};
        vm.dataId = stateParams.dataId;

        vm.fields = [
            {name: "Target Id", value: "target_id", table: true},
            {name: "Target Table", value: "target_table", table: true},
            {name: "Operation", value: "operation", table: true}
        ];


        vm.previewJson = previewJson;


        function previewJson(data)
        {
            let result = "-";
            if (data)
            {
                result = JSON.parse(data.trim());
                result = formatHighlight(result);
            }
            return $sce.trustAsHtml(result);
        }
    }
})()