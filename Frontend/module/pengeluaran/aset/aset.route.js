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
        return [
            {
                state: 'pengeluaran.aset',
                config: {
                    url: '/Aset',
                    template: table,
                    controller: 'AsetController',
                    controllerAs: 'vm',
                    title: 'Aset',
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
                    menu: 'pengeluaran',
                    nav: 'aset'
                }
            }
        ];
    }
})()