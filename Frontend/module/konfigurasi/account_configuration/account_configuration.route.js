import detail from "./html/detail.html";
import form from './html/form.html';

(() => {
    "use strict";

    angular.module('app.module.konfigurasi.account_configuration')
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
                state: 'konfigurasi.account_configuration',
                config: {
                    url: '/Account Configuration',
                    template: detail,
                    controller: 'AccountConfigurationController',
                    controllerAs: 'vm',
                    title: 'Account Configuration',
                    menu: 'konfigurasi',
                    nav: 'account_configuration'
                }
            },
            {
                state: 'konfigurasi.account_configuration_form',
                config: {
                    url: '/Account Configuration/form/:dataId',
                    template: form,
                    controller: 'AccountConfigurationController',
                    controllerAs: 'vm',
                    title: 'Form Account Configuration',
                    menu: 'konfigurasi',
                    nav: 'account_configuration'
                }
            }
        ];
    }
})()