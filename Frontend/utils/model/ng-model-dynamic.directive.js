(() => {
    "use strict";

    angular.module('app.utils')
        .directive('ngModelDynamic', ngModelDynamic);

    ngModelDynamic.$inject = ['$compile', '$timeout'];

    function ngModelDynamic($compile, $timeout)
    {
        return {
            restrict: 'A',
            link: link
        };

        function link(scope, element, attrs)
        {
            element.removeAttr('ng-model-dynamic');
            element.attr('ng-model', attrs.ngModelDynamic);

            if (attrs.inputNumber)
            {
                element.removeAttr('input-number');
                element.attr('app-number-input', attrs.inputNumber);
                element.attr('app-number-validation', attrs.inputNumber);
            }

            if (attrs.inputDate)
            {
                element.removeAttr('input-date');
                element.attr('app-date-picker', attrs.inputDate);
            }

            if (attrs.inputTime)
            {
                element.removeAttr('input-time');
                element.attr('app-time-picker', attrs.inputTime);
            }

            if (attrs.option)
            {
                element.removeAttr('option');
                element.attr('ng-options', 'item.value as item.label for item in field.selection');
            }

            if (attrs.inputAutocomplete)
            {
                element.removeAttr('input-autocomplete');
                // element.attr('app-autocomplete', attrs.inputAutocomplete);
                element.attr('app-input-select', attrs.inputAutocomplete);
            }

            if (attrs.inputFile)
            {
                element.removeAttr('input-file');
                element.attr('base-sixty-four-input', attrs.inputFile);
                element.attr('app-file-validation', attrs.inputFile);
            }

            if (attrs.ngRequired)
            {
                element.attr('ng-required', attrs.ngRequired);
            }

            $compile(element)(scope);
        }
    }
})()