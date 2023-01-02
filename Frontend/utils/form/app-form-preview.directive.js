import formPreview from "./form-preview.html";
import filePreview from "./file-preview.html";

(() => {
    "use strict";

    angular.module('app.utils')
        .directive('appFormPreview', appFormPreview);

    
    appFormPreview.$inject = ['$compile'];

    function appFormPreview($compile)
    {
        let directive = {
            restrict: 'E',
            template: formPreview,
            scope: {
                type: '@',
                value: '='
            },
            link: link
        };

        return directive;

        function link(scope)
        {
            scope.preview = preview;


            function preview()
            {
                $compile(filePreview)(scope);
            }
        }
    }
})()