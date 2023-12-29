import monthSelectPlugin from 'flatpickr/dist/plugins/monthSelect';
import '../../../node_modules/flatpickr/dist/plugins/monthSelect/style.css';

(() => {
    "use strict";

    angular.module('app.utils')
        .directive('appMonthPicker', appMonthPicker);

    appMonthPicker.$inject = ['flatpickr'];

    function appMonthPicker(flatpickr)
    {
        return {
            restrict: 'A',
            require: 'ngModel',
            link: link
        };

        function link(scope, elem, attrs, ngModelCtrl)
        {
            console.log("Working ?");

            let datepicker = flatpickr(elem, {
                plugins: [new monthSelectPlugin({shorthand: false, dateFormat: "Y-m-d", altFormat: "M Y"})]
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
                    displayValue = datepicker.parseDate(displayValue, "Y-m");
                    displayValue = datepicker.formatDate(displayValue, "m/Y");
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
                modelVal = datepicker.parseDate(modelVal, "m/Y");
                modelVal = datepicker.formatDate(modelVal, "Y-m");

                return modelVal;
            }
        }
    }
})()