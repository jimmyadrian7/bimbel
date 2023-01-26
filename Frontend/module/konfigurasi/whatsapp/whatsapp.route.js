import table from "./html/table.html";
import detail from "./html/detail.html";
import form from './html/form.html';

(() => {
    "use strict";

    angular.module('app.module.konfigurasi.whatsapp')
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
                state: 'konfigurasi.whatsapp',
                config: {
                    url: '/Whatsapp',
                    template: table,
                    controller: 'WhatsappController',
                    controllerAs: 'vm',
                    title: 'Whatsapp',
                    menu: 'konfigurasi',
                    nav: 'whatsapp'
                }
            },
            {
                state: 'konfigurasi.whatsapp_detail',
                config: {
                    url: '/Whatsapp/{dataId}',
                    template: detail,
                    controller: 'WhatsappController',
                    controllerAs: 'vm',
                    title: 'Detail Whatsapp',
                    menu: 'konfigurasi',
                    nav: 'whatsapp'
                }
            },
            {
                state: 'konfigurasi.whatsapp_form',
                config: {
                    url: '/Whatsapp/form/:dataId',
                    template: form,
                    controller: 'WhatsappController',
                    controllerAs: 'vm',
                    title: 'Form Whatsapp',
                    menu: 'konfigurasi',
                    nav: 'whatsapp'
                }
            }
        ];
    }
})()