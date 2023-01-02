(() => {
    "use strict";

    angular.module('app.utils')
        .directive('appDateFormat', appDateFormat);

    appDateFormat.$inject = ["moment"];

    function appDateFormat(moment)
    {
        return {
            restrict: 'A',
            link: link,
            scope: {
                appDateFormat: "="
            },
            template: "{{ formatDate(appDateFormat) }}"
        };

        function link(scope)
        {
            scope.formatDate = formatDate;

            function formatDate(val)
            {
                let displayValue = "-";

                if (val && val != '-')
                {
                    displayValue = moment(val, "YYYY-MM-DD").format("DD/MM/YYYY");
                }

                return displayValue;
            }
        }
    }
})()