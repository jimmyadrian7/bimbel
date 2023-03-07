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
            scope: true,
            link: link
        };

        return directive;

        function link(scope, elemenet, attrs)
        {
            scope.type = attrs.type;
            scope.expr = attrs.expr;
            scope.required = (attrs.required?.toLowerCase?.() === 'true');
            scope.name = attrs.name;
        }
    }
})()