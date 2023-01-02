(() => {
    "use strict";

    angular.module('app.utils')
        .directive('appAutocomplete', appAutocomplete);

    appAutocomplete.$inject = ['autoComplete', '$timeout', 'req'];

    function appAutocomplete(autoComplete, $timeout, req)
    {
        return {
            restrict: 'A',
            require: 'ngModel',
            link: link
        };

        function link(scope, elem, attrs, ngModelCtrl)
        {
            let elem_id = "#" + attrs.id;
            let selectedObj = {};
            let autoCompleteJS = false;

            const config = {
                selector: elem_id,
                debounce: 300,
                data: {
                    src: getData,
                    keys: ["nama"],
                    cache: false
                },
                resultItem: {
                    highlight: true,
                },
                resultsList: {
                    element: (list, data) => {
                        if (!data.results.length) {
                            const message = document.createElement("div");
                            message.setAttribute("class", "p-3");
                            message.innerHTML = `<span>Found No Results for "${data.query}"</span>`;
                            list.appendChild(message);
                        }
                    },
                    noResults: true
                }
            };

            $timeout(activate, 0);

            ngModelCtrl.$formatters.push((modelValue) => {
                return setDisplay(modelValue, true);
            });

            ngModelCtrl.$parsers.push((viewValue) => {
                setDisplay(viewValue);
                return setModel(viewValue);
            });

            function activate()
            {
                autoCompleteJS = new autoComplete(config);
                
                elem[0].addEventListener("selection", (e) => {
                    selectedObj = e.detail.selection.value;
                    setDisplay(selectedObj.nama);
                });
            }

            function getData(query)
            {
                let url = `${attrs.url}?query=${query}`;
                return req.get(url);
            }

            function setDisplay(displayValue, formatter)
            {
                if (!displayValue) {
                    return "";
                }

                if (typeof displayValue == 'object')
                {
                    selectedObj = angular.copy(displayValue);
                    displayValue = selectedObj.nama;
                    ngModelCtrl.$setViewValue(selectedObj.id);
                }
                
                if (typeof formatter !== 'undefined')
                {
                    return displayValue || "";
                }
                else
                {
                    elem.val(displayValue || "");
                }
            }

            function setModel(modelVal)
            {
                return selectedObj.id;
            }
        }
    }
})()