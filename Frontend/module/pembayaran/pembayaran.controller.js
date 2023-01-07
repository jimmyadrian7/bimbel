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
            if (session.isSuperUser())
            {
                vm.menus = [
                    {name: "Pembiayaan", list: 'pembayaran.pembiayaan', nav: 'pembiayaan'},
                    {name: "Diskon", list: 'pembayaran.diskon', nav: 'diskon'},
                    {name: "Tagihan", list: 'pembayaran.tagihan', nav: 'tagihan'},
                    // {name: "Transaksi", list: 'pembayaran.transaksi', nav: 'transaksi'},
                    {name: "Iuran", list: 'pembayaran.iuran', nav: 'iuran'}
                ];
            }
            else
            {
                vm.menus = [
                    {name: "Tagihan", list: 'pembayaran.tagihan', nav: 'tagihan'}
                ];

                if (session.isGuru())
                {
                    vm.menus.push({name: "Pembiayaan", list: 'pembayaran.pembiayaan', nav: 'pembiayaan'});
                }
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