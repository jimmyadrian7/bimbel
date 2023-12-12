import table from "./html/table.html";
import detail from "./html/detail.html";
import form from "./html/form.html";

(() => {
    "use strict";

    angular.module('app.module.siswa.deposit')
        .run(appRun);

    appRun.$inject =['routerHelper', 'req'];

    function appRun(routerHelper, req)
    {
        routerHelper.configureStates(getStates());
    }

    function getStates()
    {
        return [
            {
                state: 'siswa.deposit',
                config: {
                    url: '/Deposit',
                    template: table,
                    controller: 'DepositController',
                    controllerAs: 'vm',
                    title: 'Deposit',
                    menu: 'siswa',
                    nav: 'deposit'
                }
            },
            {
                state: 'siswa.deposit_detail',
                config: {
                    url: '/Deposit/{dataId}',
                    template: detail,
                    controller: 'DepositController',
                    controllerAs: 'vm',
                    title: 'Detail Deposit',
                    menu: 'siswa',
                    nav: 'deposit'
                }
            },
            {
                state: 'siswa.deposit_form',
                config: {
                    url: '/Deposit/{dataId}',
                    template: form,
                    controller: 'DepositController',
                    controllerAs: 'vm',
                    title: 'Form Deposit',
                    menu: 'siswa',
                    nav: 'deposit'
                }
            }
        ];
    }
})()