(() => {
    "use strict";
    
    angular.module('app.utils')
        .directive('appNumberInput', appNumberInput);


    function appNumberInput()
    {
        return {
            restrict: 'A',
            require: 'ngModel',
            link: link
        }

        function link(scope, elem, attrs, ngModelCtrl)
        {
            ngModelCtrl.$formatters.push((modelValue) => {
                return setDisplayNumber(modelValue, true);
            });

            ngModelCtrl.$parsers.push((viewValue) => {
                setDisplayNumber(viewValue);
                return setModelNumber(viewValue);
            });

            elem.bind('keyup focus', function() {
                setDisplayNumber(elem.val());
            });

            function setDisplayNumber(val, formatter)
            {
                var valStr, displayValue;
        
                if (typeof val === 'undefined') {
                  return 0;
                }
        
                valStr = val.toString();
                displayValue = valStr.replace(/\./g, '');
                displayValue = parseInt(displayValue);
                displayValue = (!isNaN(displayValue)) ? displayValue : 0;
                displayValue = displayValue.toFixed(0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        
                if (typeof formatter !== 'undefined')
                {
                    return (displayValue === '') ? 0 : displayValue;
                }
                else
                {
                    elem.val((displayValue === '0') ? '' : displayValue);
                }
            }

            function setModelNumber(val)
            {
                var modelNum = val.toString().replace(/\./g, '').replace(/[A-Za-z]/g, '');
                modelNum = parseFloat(modelNum);
                modelNum = (!isNaN(modelNum)) ? modelNum : 0;
                if (modelNum.toString().indexOf('.') !== -1)
                {
                  modelNum = Math.round((modelNum + 0.00001) * 100) / 100;
                }
                if (attrs.positive)
                {
                  modelNum = Math.abs(modelNum);
                }
                return modelNum;
            }        
        }
    }
})()