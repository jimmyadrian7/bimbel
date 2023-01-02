(() => {
    "use strict";

    angular.module('app.module.web')
        .controller('WebController', WebController);

    WebController.$inject = ['$state'];

    function WebController(state)
    {
        let vm = this;

        vm.menus = [
            {name: "Pengumuman", list: 'web.pengumuman', nav: 'pengumuman'},
            {name: "Promo", list: 'web.promo', nav: 'promo'},
            {name: "Testimoni", list: 'web.testimoni', nav: 'testimoni'},
            {name: "Konfigurasi", list: 'web.konfigurasi', nav: 'konfigurasi'}
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