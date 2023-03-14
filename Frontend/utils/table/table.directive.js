import table from './table.html';
import filterHtml from "./modal/filter.html";
import sortHtml from "./modal/sort.html";

(() => {
    "use strict";

    angular.module('app.utils')
        .directive('appTable', appTable);

    appTable.$inject = ['req', '$state', '$parse', '$compile'];

    function appTable(req, $state, $parse, $compile)
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

        controllerFunc.$inject = ['$scope', 'session', '$location', '$timeout'];

        return directive;

        function controllerFunc(scope, session, $location, $timeout)
        {
            let vm = this;
            
            vm.title = `Tabel ${$state.current.title}`;
            vm.data = [];
            vm.oldFields = [];
            vm.filterData = [
                {field: "", operation: "", value: "", selected: false}
            ];
            vm.appliedFilter = [
                {field: "", operation: "", value: "", selected: false}
            ];
            vm.sortData = [
                {field: "", type: ""}
            ];
            vm.appliedSort = [
                {field: "", type: ""}
            ];

            vm.isFilter = false;
            vm.isSort = false;
            vm.myModal = false;

            vm.tambahData = tambahData;
            vm.getValue = getValue;
            vm.changePage = changePage;
            vm.goDetail = goDetail;
            vm.getValue = getValue;

            vm.showFilter = showFilter;
            vm.changeField = changeField;
            vm.tambahFilter = tambahFilter;
            vm.deleteFilter = deleteFilter;
            vm.clearFilter = clearFilter;
            vm.applyFilter = applyFilter;

            vm.showSort = showSort;
            vm.tambahSort = tambahSort;
            vm.deleteSort = deleteSort;
            vm.clearSort = clearSort;
            vm.applySort = applySort;

            vm.currentPage = $location.search().page || 1;
            vm.currentPage = parseInt(vm.currentPage);
            vm.lastPage = 1;
            vm.page_array = [];

            
            vm.sortOptions = [
                {label: "Ascending (A-Z/1-10)", value: "ASC"},
                {label: "Descending (Z-A/10-1)", value: "DESC"}
            ];


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
                vm.oldFields = vm.fields;
                vm.fields = vm.fields.filter((field) => {
                    return field.table;
                });
            }

            function getData()
            {
                let url = `${vm.table}`;
                let data = {};
                let method = "get";

                if (vm.searchValue)
                {
                    url = `${url}&search=${vm.searchValue}`;
                }

                if (vm.isFilter || vm.isSort)
                {
                    method = "post";
                    if (vm.isFilter)
                    {
                        data.filter = vm.appliedFilter;
                    }

                    if (vm.isSort)
                    {
                        data.sort = vm.appliedSort;
                    }

                    url = `${vm.table}/custom`;
                }

                url = `${url}?pagination=1&page=${vm.currentPage}`;

                if (method == "get")
                {
                    req.get(url).then(response => {
                        vm.data = response.data || [];
                        vm.lastPage = response.last_page;
                        generatePage(response.last_page);
                    });
                }
                else
                {
                    req.post(url, data).then(response => {
                        vm.data = response.data || [];
                        vm.lastPage = response.last_page;
                        generatePage(response.last_page);
                    });
                }
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


            function showFilter()
            {
                vm.filterData = angular.copy(vm.appliedFilter);

                vm.fieldOptions = vm.oldFields.map((value) => {
                    let opt = {
                        label: value.name,
                        value: value.value,
                        type: value.type,
                        selection: value.selection,
                        operation: [
                            {value: "=", label: "="},
                            {value: "like", label: "like"}
                        ]
                    };

                    if (opt.type == "autocomplete")
                    {
                        opt.value =  opt.value.split("_");
                        opt.value = opt.value[0] + ".orang.nama";
                    }


                    if (opt.type == "selection" || opt.type == "date" || opt.type == "boolean")
                    {
                        opt.operation = [{value: "=", label: "="}];
                    }

                    if (opt.type == "number")
                    {
                        opt.operation = [
                            {value: "=", label: "="},
                            {value: "<", label: "<"},
                            {value: "<=", label: "<="},
                            {value: ">", label: ">"},
                            {value: ">=", label: ">="}
                        ];
                    }

                    if (opt.type == "autocomplete" || opt.type == "textarea")
                    {
                        opt.type = undefined;
                    }

                    return opt;
                }).filter((val) => {
                    return val.type != 'file';
                });
                
                vm.myModal = $compile(filterHtml)(scope);
            }

            function changeField(idx)
            {
                let selected = vm.filterData[idx].field;
                let selectedData = getSelectedOption(selected);

                vm.filterData[idx].selected = selectedData;

            }

            function getSelectedOption(field)
            {
                let result = false;

                vm.fieldOptions.forEach((val) => {
                    if (val.value == field)
                    {
                        result = val;
                    }
                });

                return result;
            }

            function tambahFilter()
            {
                vm.filterData.push({field: "", operation: "", value: "", selected: false});
            }

            function deleteFilter(idx)
            {
                vm.filterData.splice(idx, 1);
            }

            function clearFilter()
            {
                vm.filterData = [
                    {field: "", operation: "", value: "", selected: false}
                ];
            }

            function applyFilter()
            {
                vm.appliedFilter = angular.copy(vm.filterData);

                if (vm.appliedFilter.length == 1 && (!vm.appliedFilter[0].field || !vm.appliedFilter[0].operation || !vm.appliedFilter[0].value))
                {
                    vm.isFilter = false;
                }
                else
                {
                    vm.isFilter = true;
                }

                $timeout(() => {getData();}, 500);
            }


            function showSort()
            {
                vm.sortData = angular.copy(vm.appliedSort);

                vm.fieldOptions = vm.oldFields.map((value) => {
                    let opt = {label: value.name, value: value.value};

                    if (value.type == "autocomplete")
                    {
                        opt.value =  opt.value.split("_");
                        opt.value = opt.value[0] + ".orang.nama";
                    }

                    return opt;
                }).filter((val) => {
                    return val.type != 'file' && val.value.split(".").length == 1;
                });
                
                vm.myModal = $compile(sortHtml)(scope);
            }

            function tambahSort()
            {
                vm.sortData.push({field: "", type: ""});
            }

            function deleteSort(idx)
            {
                vm.sortData.splice(idx, 1);
            }

            function clearSort()
            {
                vm.sortData = [
                    {field: "", type: ""}
                ];
            }

            function applySort()
            {
                vm.appliedSort = angular.copy(vm.sortData);

                if (vm.appliedSort.length == 1 && (!vm.appliedSort[0].field || !vm.appliedSort[0].type))
                {
                    vm.isSort = false;
                }
                else
                {
                    vm.isSort = true;
                }

                

                $timeout(() => {getData();}, 500);
            }
        }
    }
})()