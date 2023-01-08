(() => {
    "use strict";

    angular.module('app.module.laporan')
        .controller('LaporanController', LaporanController);

    LaporanController.$inject = ['$state'];

    function LaporanController(state)
    {
        let vm = this;

        vm.menus = [
            {name: "Laba Rugi", list: 'laporan.laba_rugi', nav: 'laba_rugi'},
            {name: "Gaji Guru", list: 'laporan.gaji_guru', nav: 'gaji_guru'},
            {name: "Pendapatan", list: 'laporan.pendapatan', nav: 'pendapatan'},
            {name: "Pengeluaran", list: 'laporan.pengeluaran', nav: 'pengeluaran'},
            {name: "Deposit", list: 'laporan.deposit', nav: 'deposit'},
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