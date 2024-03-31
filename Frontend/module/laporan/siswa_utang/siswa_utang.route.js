import laporan from "../html/laporan_template.html";

(() => {
    "use strict";

    angular.module('app.module.laporan.siswa_utang')
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
                state: 'laporan.siswa_utang',
                config: {
                    url: '/Siswa Utang',
                    template: laporan,
                    controller: 'SiswaUtangController',
                    controllerAs: 'vm',
                    title: 'Siswa Utang',
                    menu: 'laporan',
                    nav: 'siswa_utang',
                    resolve: {
                        kursusOpt: getKursusOptions
                    }
                }
            }
        ];
    }
})()