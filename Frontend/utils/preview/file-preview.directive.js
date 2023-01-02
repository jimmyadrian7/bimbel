import filePreview from "./file-preview.html";

(() => {
    "use strict";

    angular.module('app.utils')
        .directive('appModalPreview', appModalPreview);

    
    appModalPreview.$inject = ['$compile'];

    function appModalPreview($compile)
    {
        let directive = {
            restrict: 'E',
            template: filePreview,
            scope: {
                value: '='
            },
            link: link
        };

        return directive;

        function link(scope, $element)
        {
            let container = $element[0].querySelector("#myPdf");
            container.setAttribute('src', `data:${scope.value.filetype};base64, ${scope.value.base64}#page=1&zoom=60`);
        }
    }
})()