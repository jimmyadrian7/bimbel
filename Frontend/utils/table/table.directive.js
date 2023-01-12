import table from './table.html';

(() => {
    "use strict";

    angular.module('app.utils')
        .directive('appTable', appTable);

    appTable.$inject = ['req', '$state', '$parse'];

    function appTable(req, $state, $parse)
    {
        let directive = {
            restrict: 'E',
            template: table,
            controller: controllerFunc,
            controllerAs: 'vm',
            scope: true,
            replace: true,
            bindToController: {
                table: '@',
                form: '@',
                detail: '@',
                fields: '='
            },
            transclude: {
                button: '?appTableButton'
            }
        };

        controllerFunc.$inject = ['$scope', 'session'];

        return directive;

        function controllerFunc(scope, session)
        {
            let vm = this;
            
            vm.addable = false;
            vm.title = `Tabel ${$state.current.title}`;
            vm.data = [];

            vm.tambahData = tambahData;
            vm.getValue = getValue;
            vm.goDetail = goDetail;
            vm.getValue = getValue;

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
                let parent = false;
                let menuKode = $state.current.name;
                if ($state.current.nav)
                {
                    menuKode = $state.current.nav;
                    parent = $state.current.menu;
                }

                let activeMenu = session.getMenu(menuKode, parent);
                if (activeMenu)
                {
                    vm.addable = activeMenu.create;

                    if (['deposit', 'user'].includes(menuKode))
                    {
                        vm.addable = false;
                    }
                }

                getTableFields();
                getData();
            }

            function getTableFields()
            {
                vm.fields = vm.fields.filter((field) => {
                    return field.table;
                });
            }

            function getData()
            {
                req.get(vm.table).then(response => {
                    vm.data = response.data || [];
                });
            }

            function tambahData()
            {
                $state.go(vm.form);
            }

            function goDetail(data)
            {
                $state.go(vm.detail, {dataId: data.id});
            }

            function getValue(data, field)
            {
                let result = $parse(field.value)(data);

                if (field.type == 'selection')
                {
                    result = getLabel(result, field.selection);
                }

                if (field.type == 'autocomplete')
                {
                    result = $parse(field.valueName + ".nama")(data);
                }

                if (field.type == 'file')
                {
                    result = $parse(field.value + ".filename")(data);
                }

                return result || "-";
            }

            function getLabel(val, arr)
            {
                for (let index = 0; index < arr.length; index++) {
                    if (arr[index].value == val)
                    {
                        return arr[index].label;
                    }
                }
            }
        }
    }
})()