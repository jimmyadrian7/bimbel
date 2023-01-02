(() => {
    "use strict";

    angular.module('app.utils')
        .directive('appDatePicker', appDatePicker);

    appDatePicker.$inject = ['flatpickr'];

    function appDatePicker(flatpickr)
    {
        return {
            restrict: 'A',
            require: 'ngModel',
            link: link
        };

        function link(scope, elem, attrs, ngModelCtrl)
        {
            let datepicker = flatpickr(elem, {
                allowInput: true,
                dateFormat: "d/m/Y"
            });

            ngModelCtrl.$formatters.push((modelValue) => {
                return setDisplay(modelValue, true);
            });

            ngModelCtrl.$parsers.push((viewValue) => {
                setDisplay(viewValue);
                return setModel(viewValue);
            });

            elem.bind('keyup focus', function() {
                setDisplay(elem.val());
            });

            function setDisplay(val, formatter)
            {
                let displayValue = val;

                if (!val) {
                    return "";
                }
                
                if (displayValue.split("-").length > 1)
                {
                    displayValue = datepicker.parseDate(displayValue, "Y-m-d");
                    displayValue = datepicker.formatDate(displayValue, "d/m/Y");
                }

                datepicker.setDate(displayValue);

                if (typeof formatter !== 'undefined')
                {
                    return (displayValue === '') ? '' : displayValue;
                }
                else
                {
                    elem.val((displayValue === '') ? '' : displayValue);
                }
            }

            function setModel(val)
            {
                let modelVal = val;
                modelVal = datepicker.parseDate(modelVal, "d/m/Y");
                modelVal = datepicker.formatDate(modelVal, "Y-m-d");

                return modelVal;
            }
        }
    }
})()