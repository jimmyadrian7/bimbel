import table from "./html/table.html";
import detail from "./html/detail.html";
import form from './html/form.html';

(() => {
    "use strict";

    angular.module('app.module.pengeluaran.modal')
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
                state: 'pengeluaran.modal',
                config: {
                    url: '/Modal',
                    template: table,
                    controller: 'ModalController',
                    controllerAs: 'vm',
                    title: 'Modal',
                    resolve: {
                        kursusOptions: getKursusOptions
                    },
                    menu: 'pengeluaran',
                    nav: 'modal'
                }
            },
            {
                state: 'pengeluaran.modal_detail',
                config: {
                    url: '/Modal/{dataId}',
                    template: detail,
                    controller: 'ModalController',
                    controllerAs: 'vm',
                    title: 'Detail Modal',
                    resolve: {
                        kursusOptions: getKursusOptions
                    },
                    menu: 'pengeluaran',
                    nav: 'modal'
                }
            },
            {
                state: 'pengeluaran.modal_form',
                config: {
                    url: '/Modal/form/:dataId',
                    template: form,
                    controller: 'ModalController',
                    controllerAs: 'vm',
                    title: 'Form Tabungan Aset',
                    resolve: {
                        kursusOptions: getKursusOptions
                    },
                    menu: 'pengeluaran',
                    nav: 'modal'
                }
            }
        ];
    }
})()