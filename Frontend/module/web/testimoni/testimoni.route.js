import table from "./html/table.html";
import detail from "./html/detail.html";
import form from './html/form.html';

(() => {
    "use strict";

    angular.module('app.module.web.testimoni')
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
                state: 'web.testimoni',
                config: {
                    url: '/Testimoni',
                    template: table,
                    controller: 'TestimoniController',
                    controllerAs: 'vm',
                    title: 'Testimoni',
                    menu: 'web',
                    nav: 'testimoni'
                }
            },
            {
                state: 'web.testimoni_detail',
                config: {
                    url: '/Testimoni/{dataId}',
                    template: detail,
                    controller: 'TestimoniController',
                    controllerAs: 'vm',
                    title: 'Detail Testimoni',
                    menu: 'web',
                    nav: 'testimoni'
                }
            },
            {
                state: 'web.testimoni_form',
                config: {
                    url: '/Testimoni/form/:dataId',
                    template: form,
                    controller: 'TestimoniController',
                    controllerAs: 'vm',
                    title: 'Form Testimoni',
                    menu: 'web',
                    nav: 'testimoni'
                }
            }
        ];
    }
})()