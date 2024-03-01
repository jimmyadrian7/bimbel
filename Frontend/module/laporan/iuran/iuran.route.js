import laporan from "../html/laporan_template.html";

(() => {
    "use strict";

    angular.module('app.module.laporan.iuran')
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
                state: 'laporan.iuran',
                config: {
                    url: '/Iuran',
                    template: laporan,
                    controller: 'IuranReportController',
                    controllerAs: 'vm',
                    title: 'Iuran',
                    menu: 'laporan',
                    nav: 'iuran',
                    resolve: {
                        guruOpt: getGuruOptions,
                        kursusOpt: getKursusOptions
                    }
                }
            }
        ];
    }
})()