import formInput from "./form-input.html";

(() => {
    "use strict";

    angular.module('app.utils')
        .directive('appFormInput', appFormInput);

    function appFormInput()
    {
        let directive = {
            restrict: 'E',
            template: formInput,
            scope: false,
            link: link
        };

        return directive;

        function link(scope, elemenet, attrs)
        {
            scope.type = attrs.type;
            scope.expr = attrs.expr;
        }
    }
})()