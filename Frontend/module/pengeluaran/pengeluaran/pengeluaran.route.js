import table from "./html/table.html";
import detail from "./html/detail.html";
import form from './html/form.html';

(() => {
    "use strict";

    angular.module('app.module.pengeluaran.pengeluaran')
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
                state: 'pengeluaran.pengeluaran',
                config: {
                    url: '/Pengeluaran',
                    template: table,
                    controller: 'PengeluaranController',
                    controllerAs: 'vm',
                    title: 'Pengeluaran',
                    resolve: {
                        kursusOptions: getKursusOptions
                    },
                    menu: 'pengeluaran',
                    nav: 'pengeluaran'
                }
            },
            {
                state: 'pengeluaran.pengeluaran_detail',
                config: {
                    url: '/Pengeluaran/{dataId}',
                    template: detail,
                    controller: 'PengeluaranController',
                    controllerAs: 'vm',
                    title: 'Detail Pengeluaran',
                    resolve: {
                        kursusOptions: getKursusOptions
                    },
                    menu: 'pengeluaran',
                    nav: 'pengeluaran'
                }
            },
            {
                state: 'pengeluaran.pengeluaran_form',
                config: {
                    url: '/Pengeluaran/form/:dataId',
                    template: form,
                    controller: 'PengeluaranController',
                    controllerAs: 'vm',
                    title: 'Form Pengeluaran',
                    resolve: {
                        kursusOptions: getKursusOptions
                    },
                    menu: 'pengeluaran',
                    nav: 'pengeluaran'
                }
            }
        ];
    }
})()