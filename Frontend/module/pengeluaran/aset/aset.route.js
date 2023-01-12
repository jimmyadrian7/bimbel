import table from "./html/table.html";
import detail from "./html/detail.html";
import form from './html/form.html';

(() => {
    "use strict";

    angular.module('app.module.pengeluaran.aset')
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
                state: 'pengeluaran.aset',
                config: {
                    url: '/Aset',
                    template: table,
                    controller: 'AsetController',
                    controllerAs: 'vm',
                    title: 'Aset',
                    resolve: {
                        kursusOptions: getKursusOptions
                    },
                    menu: 'pengeluaran',
                    nav: 'aset'
                }
            },
            {
                state: 'pengeluaran.aset_detail',
                config: {
                    url: '/Aset/{dataId}',
                    template: detail,
                    controller: 'AsetController',
                    controllerAs: 'vm',
                    title: 'Detail Aset',
                    resolve: {
                        kursusOptions: getKursusOptions
                    },
                    menu: 'pengeluaran',
                    nav: 'aset'
                }
            },
            {
                state: 'pengeluaran.aset_form',
                config: {
                    url: '/Aset/form/:dataId',
                    template: form,
                    controller: 'AsetController',
                    controllerAs: 'vm',
                    title: 'Form Aset',
                    resolve: {
                        kursusOptions: getKursusOptions
                    },
                    menu: 'pengeluaran',
                    nav: 'aset'
                }
            }
        ];
    }
})()