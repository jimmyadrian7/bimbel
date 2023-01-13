import navbar from "./html/navbar.html";

(() => {
    "use strict";

    angular.module('app.layout').directive('appNavbar', appNavbar);

    function appNavbar()
    {
        return {
            restrict: 'E',
            link: link,
            scope: true,
            template: navbar
        };

        function link(scope, element)
        {
            const iconNavbarSidenav = document.getElementById('iconNavbarSidenav');
            const sidenav = document.getElementById('sidenav-main');
            const iconSidenav = document.getElementById('iconSidenav');
            let body = document.getElementsByTagName('body')[0];
            let className = 'g-sidenav-pinned';
            
            if (iconNavbarSidenav) {
                iconNavbarSidenav.addEventListener("click", toggleSidenav);
            }

            if (iconSidenav) {
                iconSidenav.addEventListener("click", toggleSidenav);
            }


            function toggleSidenav() {
                if (body.classList.contains(className)) {
                    body.classList.remove(className);
                    setTimeout(function() {
                        sidenav.classList.remove('bg-white');
                    }, 100);
                    sidenav.classList.remove('bg-transparent');
                } else {
                    body.classList.add(className);
                    sidenav.classList.add('bg-white');
                    sidenav.classList.remove('bg-transparent');
                    iconSidenav.classList.remove('d-none');
                }
            }
        }
    }

})()