(() => {
    "use strict";

    angular.module('app.module.siswa')
        .controller('BaseSiswaController', BaseSiswaController);

    BaseSiswaController.$inject = ['$state', 'session'];

    function BaseSiswaController(state, session)
    {
        let vm = this;

        vm.menus = [
            {name: "Siswa", list: 'siswa.siswa', nav: 'siswa'},
            {name: "Deposit", list: 'siswa.deposit', nav: 'deposit'}
        ];

        vm.goState = goState;
        vm.isCurrent = isCurrent;

        activate();

        function activate()
        {
            if (session.isSiswa())
            {
                vm.menus.splice(0, 1);
            }
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