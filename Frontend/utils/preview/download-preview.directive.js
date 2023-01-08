import downloadPreview from "./download-preview.html";

(() => {
    "use strict";

    angular.module('app.utils')
        .directive('appDownloadPreview', appDownloadPreview);

    
    appDownloadPreview.$inject = ['$compile'];

    function appDownloadPreview($compile)
    {
        let directive = {
            restrict: 'E',
            template: downloadPreview,
            scope: {
                value: '='
            },
            link: link
        };

        return directive;

        function link(scope, $element)
        {
            scope.url = `data:${scope.value.filetype};base64, ${scope.value.base64}`;
        }
    }
})()