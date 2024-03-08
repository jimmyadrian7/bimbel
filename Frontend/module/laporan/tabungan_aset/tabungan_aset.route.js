import laporan from "../html/laporan_template.html";

(() => {
    "use strict";

    angular.module('app.module.laporan.tabungan_aset_report')
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
                state: 'laporan.tabungan_aset_report',
                config: {
                    url: '/Tabungan Aset',
                    template: laporan,
                    controller: 'TabunganAsetReportController',
                    controllerAs: 'vm',
                    title: 'Tabungan Aset',
                    menu: 'laporan',
                    nav: 'tabungan_aset_report',
                    resolve: {
                        guruOpt: getGuruOptions,
                        kursusOpt: getKursusOptions
                    }
                }
            }
        ];
    }
})()