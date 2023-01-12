import laporan from "./html/laporan.html";

(() => {
    "use strict";

    angular.module('app.module.laporan.gaji_guru')
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
                state: 'laporan.gaji_guru',
                config: {
                    url: '/Gaji Guru',
                    template: laporan,
                    controller: 'GajiGuruController',
                    controllerAs: 'vm',
                    title: 'Gaji Guru',
                    resolve: {
                        kursusOpt: getKursusOptions
                    },
                    menu: 'laporan',
                    nav: 'gaji_guru'
                }
            }
        ];
    }
})()