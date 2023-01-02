(() => {
    "use strict";

    angular.module('app.module.konfigurasi')
        .controller('KonfigurasiController', KonfigurasiController);

    KonfigurasiController.$inject = ['$state'];

    function KonfigurasiController(state)
    {
        let vm = this;

        vm.menus = [
            // {name: "Pembiayaan", list: 'konfigurasi.pembiayaan', nav: 'pembiayaan'},
            // {name: "Diskon", list: 'konfigurasi.diskon', nav: 'diskon'},

            {name: "Agama", list: 'konfigurasi.agama', nav: 'agama'},
            {name: "User", list: 'konfigurasi.user', nav: 'user'},
            {name: "Menu", list: 'konfigurasi.menu', nav: 'menu'},
            {name: "Sequance", list: 'konfigurasi.sequance', nav: 'sequance'},
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