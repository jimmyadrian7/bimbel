(() => {
    "use strict";

    angular.module('app.module.pengeluaran')
        .controller('BasePengeluaranController', BasePengeluaranController);

    BasePengeluaranController.$inject = ['$state', 'session'];

    function BasePengeluaranController(state, session)
    {
        let vm = this;

        vm.menus = [];

        vm.goState = goState;
        vm.isCurrent = isCurrent;

        activate();

        function activate()
        {
            let menu = session.getGroupMenu('pengeluaran');
            vm.menus = menu.map(value => {
                return {name: value.nama, list: 'pengeluaran.' + value.kode, nav: value.kode};
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