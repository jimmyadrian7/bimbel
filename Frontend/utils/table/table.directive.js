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
                fields: '=',
                addable: '=?',
                nosearch: '=?'
            },
            transclude: {
                button: '?appTableButton'
            }
        };

        controllerFunc.$inject = ['$scope', 'session', '$location'];

        return directive;

        function controllerFunc(scope, session, $location)
        {
            let vm = this;
            
            vm.title = `Tabel ${$state.current.title}`;
            vm.data = [];

            vm.tambahData = tambahData;
            vm.getValue = getValue;
            vm.changePage = changePage;
            vm.goDetail = goDetail;
            vm.getValue = getValue;

            vm.currentPage = $location.search().page || 1;
            vm.currentPage = parseInt(vm.currentPage);
            vm.lastPage = 1;
            vm.page_array = [];


            scope.$watch(() => vm.table, watchTable);
            scope.$watch(() => vm.searchValue, watchSearch);

            function watchTable(newVal)
            {
                if (newVal)
                {
                    activate();
                }
            }

            function activate()
            {
                if (vm.addable === undefined)
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
                let url = `${vm.table}?pagination=1&page=${vm.currentPage}`;

                if (vm.searchValue)
                {
                    url = `${url}&search=${vm.searchValue}`;
                }

                req.get(url).then(response => {
                    vm.data = response.data || [];
                    vm.lastPage = response.last_page;
                    generatePage(response.last_page);
                });
            }

            function generatePage(last_page)
            {
                let start = 1;
                let end = vm.currentPage + 2;
                let totalPage = 5;
                if (vm.currentPage > 3)
                {
                    start = vm.currentPage - 2;
                }

                if (end > last_page)
                {
                    start = last_page - 4;
                    end = last_page;
                }

                if (last_page < 5)
                {
                    start = 1;
                    end = last_page;
                    totalPage = last_page;
                }

                vm.lastPage = end;
                vm.page_array = Array.from({length: totalPage}, (_, i) => start + i);
            }

            function changePage(page)
            {
                vm.currentPage = page;
                $location.search('page', page);
                getData();
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

            function watchSearch(newVal, oldVal)
            {
                if (newVal === oldVal)
                {
                    return;
                }

                vm.currentPage = 1;
                getData();
            }
        }
    }
})()