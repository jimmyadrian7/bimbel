import menuHtml from "./html/modal/menu.html";

(() => {
    "use strict";

    angular.module('app.module.konfigurasi.role')
        .controller('RoleController', RoleController);

    RoleController.$inject = ['$stateParams', '$compile', '$scope', 'req'];

    function RoleController(stateParams, $compile, $scope, req)
    {
        let vm = this;

        vm.dataId = stateParams.dataId;
        vm.fields = [
            {name: "Kode", value: "kode", table: true},
            {name: "Nama", value: "nama", table: true}
        ];

        vm.role_menu_fields = [
            {name: "Nama", value: "menu.nama"},
            {name: "Parent", value: "menu.parent"},
            {name: "Create", value: "create", type: 'boolean'},
            {name: "Update", value: "update", type: 'boolean'},
            {name: "Delete", value: "delete", type: 'boolean'}
        ];

        vm.modal = {menu: []};
        vm.myModal = false;

        vm.tambahMenu = tambahMenu;
        vm.fetchMenu = fetchMenu;
        vm.isAdded = isAdded;
        vm.modifyMenu = modifyMenu;
        vm.removeMenu = removeMenu;

        function tambahMenu()
        {
            vm.myModal = $compile(menuHtml)($scope);
        }
        function fetchMenu()
        {
            req.get('menus').then(data => {
                vm.modal.menu = data;
            });
        }
        function isAdded(menu)
        {
            let result = false;

            for (let index = 0; index < vm.data.role_menu.length; index++) {
                const s = vm.data.role_menu[index];
                
                if (s.menu_id == menu.id)
                {
                    result = true;
                    break;
                }
            }

            return result;
        }
        function modifyMenu(menu)
        {
            let data = {
                menu_id: menu.id,
                menu: menu,
                create: 0,
                update: 0,
                delete: 0
            };
            
            vm.data.role_menu.push(data);
        }
        function removeMenu(idx)
        {
            vm.data.role_menu.splice(idx, 1);
        }
    }
})()