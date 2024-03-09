import laporan from "../html/laporan_template.html";

(() => {
    "use strict";

    angular.module('app.module.laporan.modal_report')
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
                let result = response.data.map((value) => {
                    return {value: value.id, label: value.nama}
                });

                result.unshift({value: "", label: "All"});

                return result;
            });
        };

        return [
            {
                state: 'laporan.modal_report',
                config: {
                    url: '/Modal',
                    template: laporan,
                    controller: 'ModalReportController',
                    controllerAs: 'vm',
                    title: 'Modal',
                    menu: 'laporan',
                    nav: 'modal_report',
                    resolve: {
                        kursusOpt: getKursusOptions
                    }
                }
            }
        ];
    }
})()