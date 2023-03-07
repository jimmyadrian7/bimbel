import form from "./form.html";

(() => {
    "use strict";

    angular.module('app.utils')
        .directive('appForm', appForm);

    appForm.$inject = ['req', '$state'];

    function appForm(req, state)
    {
        let directive = {
            restrict: 'E',
            template: form,
            controller: controllerFunc,
            controllerAs: 'vm',
            scope: true,
            replace: true,
            bindToController: {
                table: '@',
                detail: '@',
                list: '@',
                id: '=',
                fields: '=',
                additional: '=',
                data: '=?'
            },
            transclude: {
                header: '?appFormHeader',
                body: '?appFormBody'
            }
        };

        controllerFunc.$inject = ['$scope', '$transclude', '$element', '$parse'];

        return directive;

        function controllerFunc(scope, transclude, element, $parse)
        {
            let vm = this;

            vm.saveData = saveData;
            vm.cancelEdit = cancelEdit;
            vm.checkValidation = checkValidation;

            scope.$watch(() => vm.table, watchTable);

            function watchTable(newVal)
            {
                if (newVal)
                {
                    activate();
                }
            }

            function activate()
            {
                vm.fields = vm.fields.filter(val => !val.hidden);
                vm.fields = chunkArr();
                getDataDetail();

                transclude(scope, function(clone, scope) {
                    let additional = false;
                    for (let index = 0; index < clone.length; index++) {
                        const el = clone[index];
                        if (el.id == 'additional')
                        {
                            additional = el;
                            break;
                        }
                    }

                    if (additional)
                    {
                        element[0].querySelector('#additionalSlot').append(additional);
                    }
                });
            }

            function getDataDetail()
            {
                let autoCompleteFields = [];

                vm.fields.forEach(fields  => {
                    fields.forEach(field => {
                        if (field.type == 'autocomplete')
                        {
                            autoCompleteFields.push(field);
                        }
                    });
                });

                if (vm.id)
                {
                    req.get(`${vm.table}/${vm.id}`).then(data => {
                        autoCompleteFields.forEach(field => {
                            let autoCompleteValue = $parse(field.valueName)(data);
                            let getter = $parse(field.value);
                            getter.assign(data, autoCompleteValue);
                        });
                        vm.data = data || {};
                        scope.$parent.vm.data = vm.data;
                    });
                }
            }

            function saveData()
            {
                if (vm.id)
                {
                    vm.data.id = angular.copy(vm.id);
                    req.put(vm.table, vm.data).then(data => {
                        state.go(vm.detail, {dataId: data.id});
                    });
                }
                else
                {
                    req.post(vm.table, vm.data).then(data => {
                        state.go(vm.detail, {dataId: data.id});
                    });
                }
            }

            function checkValidation(isValid)
            {
                if (isValid)
                {
                    saveData();
                }
            }

            function cancelEdit()
            {
                if (vm.id)
                {
                    state.go(vm.detail, {dataId: vm.id});
                }
                else
                {
                    state.go(vm.list);
                }
            }

            function chunkArr()
            {
                let perChunk = vm.fields.length;

                if (vm.fields.length > 3)
                {
                    perChunk = Math.ceil(vm.fields.length / 2);
                }
                
                let result = vm.fields.reduce((resultArr, item, idx) => {
                    const chunkIndex = Math.floor(idx/perChunk);

                    if (!resultArr[chunkIndex])
                    {
                        resultArr[chunkIndex] = [];
                    }

                    resultArr[chunkIndex].push(item);

                    return resultArr;
                }, []);

                return result;
            }
        }
    }
})()