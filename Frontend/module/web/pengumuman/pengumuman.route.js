import table from "./html/table.html";
import detail from "./html/detail.html";
import form from './html/form.html';

(() => {
    "use strict";

    angular.module('app.module.web.pengumuman')
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
                state: 'web.pengumuman',
                config: {
                    url: '/Pengumuman',
                    template: table,
                    controller: 'PengumumanController',
                    controllerAs: 'vm',
                    title: 'Pengumuman',
                    menu: 'web',
                    nav: 'pengumuman'
                }
            },
            {
                state: 'web.pengumuman_detail',
                config: {
                    url: '/Pengumuman/{dataId}',
                    template: detail,
                    controller: 'PengumumanController',
                    controllerAs: 'vm',
                    title: 'Detail Pengumuman',
                    menu: 'web',
                    nav: 'pengumuman'
                }
            },
            {
                state: 'web.pengumuman_form',
                config: {
                    url: '/Pengumuman/form/:dataId',
                    template: form,
                    controller: 'PengumumanController',
                    controllerAs: 'vm',
                    title: 'Form Pengumuman',
                    menu: 'web',
                    nav: 'pengumuman'
                }
            }
        ];
    }
})()