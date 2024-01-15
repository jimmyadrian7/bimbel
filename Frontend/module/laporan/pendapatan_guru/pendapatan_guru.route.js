import laporan from "../html/laporan_template.html";

(() => {
    "use strict";

    angular.module('app.module.laporan.pendapatan_guru')
        .run(appRun);

    appRun.$inject =['routerHelper'];

    function appRun(routerHelper)
    {
        routerHelper.configureStates(getStates());
    }

    function getStates()
    {
        let getGuruOptions = (req) => {
            return req.get('gurus').then(response => {
                return response.data.map((value) => {
                    return {value: value.id, label: value.orang.nama}
                });
            });
        };

        let getKursusOptions = (req) => {
            return req.get('kursuss').then(response => {
                let result = response.data.map((value) => {
                    return {value: value.id, label: value.nama}
                });

                result.unshift({value: "", label: "All"});

                return result;
            });
        };

        return [
            {
                state: 'laporan.pendapatan_guru',
                config: {
                    url: '/Pendapatan Guru',
                    template: laporan,
                    controller: 'PendapatanGuruController',
                    controllerAs: 'vm',
                    title: 'Pendapatan Guru',
                    menu: 'laporan',
                    nav: 'pendapatan_guru',
                    resolve: {
                        guruOpt: getGuruOptions,
                        kursusOpt: getKursusOptions
                    }
                }
            }
        ];
    }
})()