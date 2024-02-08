import table from "./html/table.html";
import detail from "./html/detail.html";
import form from './html/form.html';

(() => {
    "use strict";

    angular.module('app.module.web.user_question')
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
                state: 'web.user_question',
                config: {
                    url: '/Question',
                    template: table,
                    controller: 'UserQuestionController',
                    controllerAs: 'vm',
                    title: 'Question',
                    menu: 'web',
                    nav: 'user_question'
                }
            },
            {
                state: 'web.user_question_detail',
                config: {
                    url: '/Question/{dataId}',
                    template: detail,
                    controller: 'UserQuestionController',
                    controllerAs: 'vm',
                    title: 'Detail Question',
                    menu: 'web',
                    nav: 'user_question'
                }
            },
            {
                state: 'web.user_question_form',
                config: {
                    url: '/Question/form/:dataId',
                    template: form,
                    controller: 'UserQuestionController',
                    controllerAs: 'vm',
                    title: 'Form Question',
                    menu: 'web',
                    nav: 'user_question'
                }
            }
        ];
    }
})()