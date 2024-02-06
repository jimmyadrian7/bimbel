import table from "./html/table.html";
import detail from "./html/detail.html";
import form from "./html/form.html";

(() => {
    "use strict";

    angular.module('app.module.siswa.siswa')
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
        let getRefOptions = (req) => {
            return req.get('referals').then(response => {
                return response.data.map((value) => {
                    return {value: value.id, label: value.nama}
                });
            });
        };
        let getKursusOptions = (req) => {
            return req.get('kursuss').then(response => {
                return response.data.map((value) => {
                    return {value: value.id, label: value.nama, sequance: value.sequance_pendaftaran, kode: value.kode}
                });
            });
        };

        return [
            {
                state: 'siswa.siswa',
                config: {
                    url: '/Siswa',
                    template: table,
                    controller: 'SiswaController',
                    controllerAs: 'vm',
                    title: 'Siswa',
                    menu: 'siswa',
                    nav: 'siswa',
                    resolve: {
                        agamaOptions: getAgaOptions,
                        referalOptions: getRefOptions,
                        kursusOptions: getKursusOptions
                    }
                }
            },
            {
                state: 'siswa.siswa_detail',
                config: {
                    url: '/Siswa/{dataId}',
                    template: detail,
                    controller: 'SiswaController',
                    controllerAs: 'vm',
                    title: 'Detail Siswa',
                    menu: 'siswa',
                    nav: 'siswa',
                    resolve: {
                        agamaOptions: getAgaOptions,
                        referalOptions: getRefOptions,
                        kursusOptions: getKursusOptions
                    }
                }
            },
            {
                state: 'siswa.siswa_form',
                config: {
                    url: '/Siswa/form/:dataId',
                    template: form,
                    controller: 'SiswaController',
                    controllerAs: 'vm',
                    title: 'Form Siswa',
                    menu: 'siswa',
                    nav: 'siswa',
                    resolve: {
                        agamaOptions: getAgaOptions,
                        referalOptions: getRefOptions,
                        kursusOptions: getKursusOptions
                    }
                }
            }
        ];
    }
})()