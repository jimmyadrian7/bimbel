(() => {
    "use strict";

    angular.module('app.utils')
        .directive('appInputSelect', appInputSelect);

    appInputSelect.$inject = ['$window'];

    function appInputSelect($window)
    {
        let directive = {
            restrict: 'A',
            require: 'ngModel',
            link: link
        };

        return directive;

        
        function link(scope, element, attrs, ngModelCtrl)
        {
            let el = $(element);
            let init = false;
            let baseUrl = $window.location.pathname.split('/admin')[0] + "/api";

            el.select2({
                theme: "bootstrap-5",
                dropdownParent: el.parent(),
                minimumInputLength: 1,
                ajax: {
                    url: `${baseUrl}/${attrs.url}`,
                    dataType: 'json',
                    data: (params) => { return {query: params.term} },
                    processResults: (response) => {
                        return {
                            results: response
                        }
                    }
                }
            });

            el.on('change', function() {
                if (init)
                {
                    ngModelCtrl.$setViewValue(el.select2("val"));
                    init = false;
                }
                else
                {
                    scope.$apply(function() {
                        ngModelCtrl.$setViewValue(el.select2("val"));
                    });
                }
            })


            ngModelCtrl.$formatters.push((modelValue) => {
                return setDisplay(modelValue, true);
            });

            ngModelCtrl.$parsers.push((viewValue) => {
                setDisplay(viewValue);
                return setModel(viewValue);
            });


            function setDisplay(displayValue, formatter)
            {
                if (!displayValue) {
                    return "";
                }

                if (typeof displayValue == 'object')
                {
                    init = true;
                    displayValue.text = displayValue.nama;

                    delete displayValue.nama;

                    var newOption = new Option(displayValue.text, displayValue.id, true, true);
                    
                    Object.keys(displayValue).forEach(key => {
                        if (!['id', 'text'].includes(key))
                        {
                            newOption.setAttribute(key, displayValue[key]);
                        }
                    });

                    el.append(newOption).trigger('change');
                    
                    el.trigger({
                        type: 'select2:select',
                        params: {
                            data: displayValue
                        }
                    });
                }
                
                if (typeof formatter !== 'undefined')
                {
                    return displayValue || "";
                }
                else
                {
                    element.val(displayValue || "");
                }
            }

            function setModel(modelVal)
            {
                return modelVal;
            }
        }
    }
})()