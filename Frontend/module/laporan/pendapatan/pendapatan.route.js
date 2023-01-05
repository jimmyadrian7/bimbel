import laporan from "./html/laporan.html";

(() => {
    "use strict";

    angular.module('app.module.laporan.pendapatan')
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
                state: 'laporan.pendapatan',
                config: {
                    url: '/Pendapatan',
                    template: laporan,
                    controller: 'PendapatanController',
                    controllerAs: 'vm',
                    title: 'Pendapatan',
                    menu: 'laporan',
                    nav: 'pendapatan',
                    resolve: {
                        kursusOpt: getKursusOptions
                    }
                }
            }
        ];
    }
})()