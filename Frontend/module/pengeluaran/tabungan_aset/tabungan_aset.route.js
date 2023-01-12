import table from "./html/table.html";
import detail from "./html/detail.html";
import form from './html/form.html';

(() => {
    "use strict";

    angular.module('app.module.pengeluaran.tabungan_aset')
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
                state: 'pengeluaran.tabungan_aset',
                config: {
                    url: '/tabungan Aset',
                    template: table,
                    controller: 'TabunganAsetController',
                    controllerAs: 'vm',
                    title: 'Tabungan Aset',
                    resolve: {
                        kursusOptions: getKursusOptions
                    },
                    menu: 'pengeluaran',
                    nav: 'tabungan_aset'
                }
            },
            {
                state: 'pengeluaran.tabungan_aset_detail',
                config: {
                    url: '/tabungan Aset/{dataId}',
                    template: detail,
                    controller: 'TabunganAsetController',
                    controllerAs: 'vm',
                    title: 'Detail Tabungan Aset',
                    resolve: {
                        kursusOptions: getKursusOptions
                    },
                    menu: 'pengeluaran',
                    nav: 'tabungan_aset'
                }
            },
            {
                state: 'pengeluaran.tabungan_aset_form',
                config: {
                    url: '/tabungan Aset/form/:dataId',
                    template: form,
                    controller: 'TabunganAsetController',
                    controllerAs: 'vm',
                    title: 'Form Tabungan Aset',
                    resolve: {
                        kursusOptions: getKursusOptions
                    },
                    menu: 'pengeluaran',
                    nav: 'tabungan_aset'
                }
            }
        ];
    }
})()