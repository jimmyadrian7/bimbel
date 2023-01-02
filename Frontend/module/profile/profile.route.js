import detail from "./html/detail.html";
import form from './html/form.html';

(() => {
    "use strict";

    angular.module('app.module.profile')
        .run(appRun);

    appRun.$inject =['routerHelper'];

    function appRun(routerHelper)
    {
        routerHelper.configureStates(getStates());
    }

    function getStates()
    {
        let getAgaOptions = (req) => {
            return req.get('agamas').then(response => {
                return response.data.map((value) => {
                    return {value: value.id, label: value.nama}
                });
            });
        };

        return [
            {
                state: 'profile',
                config: {
                    url: '/Profile',
                    template: detail,
                    controller: 'ProfileController',
                    controllerAs: 'vm',
                    title: 'Profile',
                    menu: 'profile',
                    resolve: {
                        agamaOptions: getAgaOptions
                    }
                }
            },
            {
                state: 'profile_form',
                config: {
                    url: '/Profile/form',
                    template: form,
                    controller: 'ProfileController',
                    controllerAs: 'vm',
                    title: 'Form Profile',
                    menu: 'profile',
                    resolve: {
                        agamaOptions: getAgaOptions
                    }
                }
            }
        ];
    }
})()