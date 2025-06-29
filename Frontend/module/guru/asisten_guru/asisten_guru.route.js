import table from "./html/table.html";
import detail from "./html/detail.html";
import form from "./html/form.html";

(() => {
    "use strict";

    angular.module('app.module.guru.asisten_guru')
        .run(appRun);

    appRun.$inject = ['routerHelper', 'req'];

    function appRun(routerHelper, req) {
        routerHelper.configureStates(getStates());
    }

    function getStates() {
        let getAgaOptions = (req) => {
            return req.get('agamas').then(response => {
                return response.data.map((value) => {
                    return { value: value.id, label: value.nama }
                });
            });
        };

        return [
            {
                state: 'guru.asisten_guru',
                config: {
                    url: '/Asisten Guru',
                    template: table,
                    controller: 'AsistenGuruController',
                    controllerAs: 'vm',
                    title: 'Asisten Guru',
                    nav: 'asisten_guru',
                    menu: 'guru',
                    resolve: {
                        agamaOptions: getAgaOptions
                    }
                }
            },
            {
                state: 'guru.asisten_guru_detail',
                config: {
                    url: '/Asisten Guru/{dataId}',
                    template: detail,
                    controller: 'AsistenGuruController',
                    controllerAs: 'vm',
                    title: 'Detail Asisten Guru',
                    nav: 'asisten_guru',
                    menu: 'guru',
                    resolve: {
                        agamaOptions: getAgaOptions
                    }
                }
            },
            {
                state: 'guru.asisten_guru_form',
                config: {
                    url: '/Asisten Guru/form/:dataId',
                    template: form,
                    controller: 'AsistenGuruController',
                    controllerAs: 'vm',
                    title: 'Form Asisten Guru',
                    nav: 'asisten_guru',
                    menu: 'guru',
                    resolve: {
                        agamaOptions: getAgaOptions
                    }
                }
            }
        ];
    }
})()