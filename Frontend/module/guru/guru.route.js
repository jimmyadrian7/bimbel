import table from "./html/table.html";
import detail from "./html/detail.html";
import form from "./html/form.html";

(() => {
    "use strict";

    angular.module('app.module.guru')
        .run(appRun);

    appRun.$inject =['routerHelper', 'req'];

    function appRun(routerHelper, req)
    {
        routerHelper.configureStates(getStates());
    }

    function getStates()
    {
        let getAgaOptions = (req) => {
            return req.get('agamas').then(response => {
                return response.data.map((value) => {
                    return {value: value.id, label: value.nama}
                });
            });
        };

        let getKursusOptions = (req) => {
            return req.get('kursuss').then(response => {
                return response.data.map((value) => {
                    return {value: value.id, label: value.nama}
                });
            });
        };

        return [
            {
                state: 'guru',
                config: {
                    url: '/Guru',
                    template: table,
                    controller: 'GuruController',
                    controllerAs: 'vm',
                    title: 'Guru',
                    menu: 'guru',
                    resolve: {
                        agamaOptions: getAgaOptions
                    }
                }
            },
            {
                state: 'guru_detail',
                config: {
                    url: '/Guru/{dataId}',
                    template: detail,
                    controller: 'GuruController',
                    controllerAs: 'vm',
                    title: 'Detail Guru',
                    menu: 'guru',
                    resolve: {
                        agamaOptions: getAgaOptions,
                        kursusOptions: getKursusOptions
                    }
                }
            },
            {
                state: 'guru_form',
                config: {
                    url: '/Guru/form/:dataId',
                    template: form,
                    controller: 'GuruController',
                    controllerAs: 'vm',
                    title: 'Form Guru',
                    menu: 'guru',
                    resolve: {
                        agamaOptions: getAgaOptions
                    }
                }
            }
        ];
    }
})()