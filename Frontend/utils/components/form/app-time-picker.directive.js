(() => {
    "use strict";

    angular.module('app.utils')
        .directive('appTimePicker', appTimePicker);

    appTimePicker.$inject = ['flatpickr'];

    function appTimePicker(flatpickr)
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
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true
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
                return val;
            }
        }
    }
})()