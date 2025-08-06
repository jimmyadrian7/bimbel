import detail from "./html/detail.html";
import form from './html/form.html';

(() => {
    "use strict";

    angular.module('app.module.konfigurasi.report_profile')
        .run(appRun);

    appRun.$inject = ['routerHelper'];

    function appRun(routerHelper) {
        routerHelper.configureStates(getStates());
    }

    function getStates() {
        return [
            {
                state: 'konfigurasi.report_profile',
                config: {
                    url: '/Report Profile',
                    template: detail,
                    controller: 'ReportProfileController',
                    controllerAs: 'vm',
                    title: 'Report Profile',
                    menu: 'konfigurasi',
                    nav: 'report_profile'
                }
            },
            {
                state: 'konfigurasi.report_profile_form',
                config: {
                    url: '/Konfigurasi/form/:dataId',
                    template: form,
                    controller: 'ReportProfileController',
                    controllerAs: 'vm',
                    title: 'Form Report Profile',
                    menu: 'konfigurasi',
                    nav: 'report_profile'
                }
            }
        ];
    }
})()