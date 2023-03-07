(() => {
    "use strict";

    angular.module('app.utils')
        .directive('appFileValidation', appFileValidation);

    function appFileValidation()
    {
        return {
            require: 'ngModel',
            link: (scope, elm, attrs, ctrl) => {
                ctrl.$validators.appFileValidation = (modelValue, viewValue) => {
                    if (ctrl.$isEmpty(modelValue) && attrs.ngRequired === "true")
                    {
                        return false;
                    }
                    else
                    {
                        return true;
                    }
                }
            }
        }
    }
})()