import editor from "./html/editor.html";

(() => {
    "use strict";

    angular.module('app.module.konfigurasi.template_kwitansi')
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
                state: 'konfigurasi.template_kwitansi',
                config: {
                    url: '/TemplateKwitansi',
                    template: editor,
                    controller: 'TemplateKwitansiController',
                    controllerAs: 'vm',
                    title: 'Template Kwitansi',
                    menu: 'konfigurasi',
                    nav: 'template_kwitansi'
                }
            }
        ];
    }
})()
