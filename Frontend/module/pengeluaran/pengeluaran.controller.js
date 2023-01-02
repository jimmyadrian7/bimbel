(() => {
    "use strict";

    angular.module('app.module.pengeluaran')
        .controller('BasePengeluaranController', BasePengeluaranController);

    BasePengeluaranController.$inject = ['$state'];

    function BasePengeluaranController(state)
    {
        let vm = this;

        vm.menus = [
            {name: "Aset", list: 'pengeluaran.aset', nav: 'aset'},
            {name: "Tabungan Aset", list: 'pengeluaran.tabungan_aset', nav: 'tabungan_aset'},
            // {name: "Gaji", list: 'pengeluaran.gaji', nav: 'gaji'},
            {name: "Pengeluaran", list: 'pengeluaran.pengeluaran', nav: 'pengeluaran'}
        ];

        vm.goState = goState;
        vm.isCurrent = isCurrent;

        activate();

        function activate()
        {
            
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