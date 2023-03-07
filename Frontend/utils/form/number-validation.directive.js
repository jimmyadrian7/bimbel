(() => {
    "use strict";

    angular.module('app.utils')
        .directive('appNumberValidation', appNumberValidation);

    function appNumberValidation()
    {
        return {
            require: 'ngModel',
            link: (scope, elm, attrs, ctrl) => {
                ctrl.$validators.appNumberValidation = (modelValue, viewValue) => {
                    if ((ctrl.$isEmpty(modelValue) || modelValue <= 0) && attrs.ngRequired === "true")
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