import inputGroup from "./app-input-group.html";

(() => {
    "use strict";

    angular.module('app.utils')
        .directive('appInputGroup', appInputGroup);

    function appInputGroup()
    {
        let directive = {
            restrict: 'E',
            template: inputGroup,
            scope: {
                expr: '@',
                placeholder: '@',
                icon: '@'
            }
        };

        return directive;
    }
})()