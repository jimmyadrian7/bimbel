import tableTemplate from "./table-template.html";
import previewHtml from "./preview.html";

(() => {
    "use strict";

    angular.module('app.utils')
        .directive('appTableTemplate', appTableTemplate);

    function appTableTemplate()
    {
        let directive = {
            restrict: 'E',
            scope: true,
            template: tableTemplate,
            controller: controllerFunc,
            controllerAs: 'vm',
            bindToController: {
                fields: '=',
                data: "="
            }
        };

        controllerFunc.$inject = ['$parse', '$compile', '$scope'];

        return directive;

        function controllerFunc($parse, $compile, scope)
        {
            let vm = this;

            vm.getValue = getValue;
            vm.preview = preview;

            function getValue(data, field)
            {
                let result = $parse(field.value)(data);

                if (field.type == 'selection')
                {
                    result = getLabel(result, field.selection);
                }

                if (field.type == 'autocomplete')
                {
                    result = $parse(field.valueName + ".nama")(data);
                }

                if (field.type == 'file')
                {
                    result = $parse(field.value + ".filename")(data);
                }

                return result || "-";
            }
            function getLabel(val, arr)
            {
                for (let index = 0; index < arr.length; index++) {
                    if (arr[index].value == val)
                    {
                        return arr[index].label;
                    }
                }
            }

            function preview(dt, field)
            {
                let data = $parse(field.value)(dt);
                vm.previewFile = data;
                $compile(previewHtml)(scope);
            }
        }
    }
})()