(() => {
    "use strict";

    angular.module('app.module.laporan')
        .controller('LaporanController', LaporanController);

    LaporanController.$inject = ['$state', 'session'];

    function LaporanController(state, session)
    {
        let vm = this;

        vm.menus = [];

        vm.goState = goState;
        vm.isCurrent = isCurrent;

        activate();

        function activate()
        {
            let menu = session.getGroupMenu('laporan');
            vm.menus = menu.map(value => {
                return {name: value.nama, list: 'laporan.' + value.kode, nav: value.kode};
            });
        }

        function goState(menu)
        {
            state.go(menu.list);
        }

        function isCurrent(menu)
        {
            let result = "text-dark";
            
            if (state.current.nav == menu.nav)
            {
                result = "text-primary";
            }
            
            return result;
        }
    }
})()