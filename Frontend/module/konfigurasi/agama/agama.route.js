import table from "./html/table.html";
import detail from "./html/detail.html";
import form from './html/form.html';

(() => {
    "use strict";

    angular.module('app.module.konfigurasi.agama')
        .run(appRun);

    appRun.$inject =['routerHelper'];

    function appRun(routerHelper)
    {
        routerHelper.configureStates(getStates());
    }

    function getStates()
    {
        return [
            {
                state: 'konfigurasi.agama',
                config: {
                    url: '/Agama',
                    template: table,
                    controller: 'AgamaController',
                    controllerAs: 'vm',
                    title: 'Agama',
                    menu: 'konfigurasi',
                    nav: 'agama'
                }
            },
            {
                state: 'konfigurasi.agama_detail',
                config: {
                    url: '/Agama/{dataId}',
                    template: detail,
                    controller: 'AgamaController',
                    controllerAs: 'vm',
                    title: 'Detail Agama',
                    menu: 'konfigurasi',
                    nav: 'agama'
                }
            },
            {
                state: 'konfigurasi.agama_form',
                config: {
                    url: '/Agama/form/:dataId',
                    template: form,
                    controller: 'AgamaController',
                    controllerAs: 'vm',
                    title: 'Form Agama',
                    menu: 'konfigurasi',
                    nav: 'agama'
                }
            }
        ];
    }
})()