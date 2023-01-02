import table from "./html/table.html";
import detail from "./html/detail.html";
import form from './html/form.html';

(() => {
    "use strict";

    angular.module('app.module.web.promo')
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
                state: 'web.promo',
                config: {
                    url: '/Promo',
                    template: table,
                    controller: 'PromoController',
                    controllerAs: 'vm',
                    title: 'Promo',
                    menu: 'web',
                    nav: 'promo'
                }
            },
            {
                state: 'web.promo_detail',
                config: {
                    url: '/Promo/{dataId}',
                    template: detail,
                    controller: 'PromoController',
                    controllerAs: 'vm',
                    title: 'Detail Promo',
                    menu: 'web',
                    nav: 'promo'
                }
            },
            {
                state: 'web.promo_form',
                config: {
                    url: '/Promo/form/:dataId',
                    template: form,
                    controller: 'PromoController',
                    controllerAs: 'vm',
                    title: 'Form Promo',
                    menu: 'web',
                    nav: 'promo'
                }
            }
        ];
    }
})()