(() => {
    "use strict";

    angular.module('app.module.pembayaran')
        .controller('PembayaranController', PembayaranController);

    PembayaranController.$inject = ['$state', 'session'];

    function PembayaranController(state, session)
    {
        let vm = this;

        vm.menus = [];

        vm.goState = goState;
        vm.isCurrent = isCurrent;

        activate();


        function activate()
        {
            let menu = session.getGroupMenu('pembayaran');
            vm.menus = menu.map(value => {
                return {name: value.nama, list: 'pembayaran.' + value.kode, nav: value.kode};
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