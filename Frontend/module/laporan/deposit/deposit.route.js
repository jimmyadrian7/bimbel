import laporan from "./html/laporan.html";

(() => {
    "use strict";

    angular.module('app.module.laporan.deposit')
        .run(appRun);

    appRun.$inject =['routerHelper'];

    function appRun(routerHelper)
    {
        routerHelper.configureStates(getStates());
    }

    function getStates()
    {
        let getKursusOptions = (req) => {
            return req.get('kursuss').then(response => {
                return response.data.map((value) => {
                    return {value: value.id, label: value.nama}
                });
            });
        };
        
        return [
            {
                state: 'laporan.deposit',
                config: {
                    url: '/Deposit',
                    template: laporan,
                    controller: 'LaporanDepositController',
                    controllerAs: 'vm',
                    title: 'Deposit',
                    resolve: {
                        kursusOpt: getKursusOptions
                    },
                    menu: 'laporan',
                    nav: 'deposit'
                }
            }
        ];
    }
})()