import laporan from "./html/laporan.html";

(() => {
    "use strict";

    angular.module('app.module.laporan.laba_rugi')
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
                state: 'laporan.laba_rugi',
                config: {
                    url: '/Laba Rugi',
                    template: laporan,
                    controller: 'LabaRugiController',
                    controllerAs: 'vm',
                    title: 'Laba Rugi',
                    resolve: {
                        kursusOpt: getKursusOptions
                    },
                    menu: 'laporan',
                    nav: 'laba_rugi'
                }
            }
        ];
    }
})()