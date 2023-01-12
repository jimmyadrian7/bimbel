import detail from "./detail.html";
import previewHtml from "./preview.html";

(() => {
    "use strict";

    angular.module('app.utils')
        .directive('appDetail', appDetail);

    appDetail.$inject = ['req', '$state'];

    function appDetail(req, state)
    {
        let directive = {
            restrict: 'E',
            template: detail,
            controller: controllerFunc,
            controllerAs: 'vm',
            scope: true,
            replace: true,
            bindToController: {
                table: '@',
                list: '@',
                edit: '@',
                id: '=',
                fields: '=',
                noback: '='
            },
            transclude: {
                title: '?appDetailTitle',
                button: '?appDetailButton',
                additional: '?appDetailAdditional'
            }
        };

        controllerFunc.$inject = ['$scope', '$parse', '$compile', 'session'];

        return directive;

        function controllerFunc(scope, $parse, $compile, session)
        {
            let vm = this;
            
            vm.data = {};
            vm.previewFile = {};

            vm.editData = editData;
            vm.deleteData = deleteData;
            vm.back = back;
            vm.getValue = getValue;
            vm.preview = preview;

            vm.editable = false;
            vm.deleteable = false;

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
                vm.fields = vm.fields.filter(value => !value.hideDetail);
                vm.fields = chunkArr();

                let parent = false;
                let menuKode = state.current.menu;
                if (state.current.nav)
                {
                    menuKode = state.current.nav;
                    parent = state.current.menu;
                }

                let activeMenu = session.getMenu(menuKode, parent);
                if (activeMenu)
                {
                    vm.editable = activeMenu.update;
                    vm.deleteable = activeMenu.delete;
                }

                getDataDetail();
            }

            function getDataDetail()
            {
                req.get(`${vm.table}/${vm.id}`).then(data => {
                    vm.data = data || {};
                    scope.$parent.vm.data = vm.data;
                });
            }

            function editData()
            {
                state.go(vm.edit, {dataId: vm.id});
            }

            function deleteData()
            {
                req.del(`${vm.table}`, {id: vm.id}).then(response => {
                    state.go(vm.list);
                });
            }

            function back()
            {
                state.go(vm.list);
            }

            function getValue(field)
            {
                let result = $parse(field.value)(vm.data);

                if (field.type == 'selection')
                {
                    result = getLabel(result, field.selection);
                }

                if (field.type == 'autocomplete')
                {
                    result = $parse(field.valueName + ".nama")(vm.data);
                }

                if (field.type == 'file')
                {
                    result = $parse(field.value + ".filename")(vm.data);
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

            function preview(field)
            {
                let data = $parse(field.value)(vm.data);
                vm.previewFile = data;
                $compile(previewHtml)(scope);
            }
        }
    }
})()