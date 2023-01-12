import laporan from "./html/laporan.html";

(() => {
    "use strict";

    angular.module('app.module.laporan.pengeluaran')
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
                state: 'laporan.pengeluaran',
                config: {
                    url: '/Pengeluaran',
                    template: laporan,
                    controller: 'LaporanPengeluaranController',
                    controllerAs: 'vm',
                    title: 'Pengeluaran',
                    resolve: {
                        kursusOpt: getKursusOptions
                    },
                    menu: 'laporan',
                    nav: 'pengeluaran'
                }
            }
        ];
    }
})()