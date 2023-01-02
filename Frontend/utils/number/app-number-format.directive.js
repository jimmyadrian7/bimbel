(() => {
    "use strict";

    angular.module('app.utils')
        .directive('appNumberFormat', appNumberFormat);

    function appNumberFormat()
    {
        return {
            restrict: 'A',
            link: link,
            scope: {
                appNumberFormat: "="
            },
            template: "{{ formatNumber(appNumberFormat) }}"
        };

        function link(scope, elem, attrs)
        {
            scope.formatNumber = formatNumber;

            function formatNumber(number)
            {
                if (isNaN(number))
                {
                    return "0";
                }
                
                return parseInt(number)
                    .toFixed(0)
                    .toString()
                    .replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
        }
    }
})()