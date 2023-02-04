import html from "./app-modal.html";

(() => {
    "use strict";

    angular.module('app.utils')
        .directive('appModal', appModal);

    appModal.$inject = ['Modal'];

    function appModal(Modal)
    {
        let directive = {
            restrict: 'E',
            template: html,
            controller: controllerFunc,
            controllerAs: 'vm',
            scope: true,
            replace: true,
            bindToController: {
                data: '=?'
            },
            transclude: {
                title: 'appModalTitle',
                body: 'appModalBody',
                footer: '?appModalFooter'
            }
        };

        controllerFunc.$inject =['$element', '$scope'];

        return directive;

        function controllerFunc(element, scope)
        {
            let vm = this;

            scope.$watch(() => vm.data, watchTable);

            function watchTable(newVal)
            {
                if (newVal)
                {
                    activate();
                }
            }

            function activate()
            {
                let m = new Modal(element[0]);

                element.on('hidden.bs.modal', () => {
                    scope.$destroy();
                    element.remove();
                });

                m.show();
            }
        }
    }
})()