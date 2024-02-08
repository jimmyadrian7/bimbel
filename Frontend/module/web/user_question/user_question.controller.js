(() => {
    "use strict";

    angular.module('app.module.web.user_question')
        .controller('UserQuestionController', UserQuestionController);

    UserQuestionController.$inject = ['$stateParams'];

    function UserQuestionController(stateParams)
    {
        let vm = this;

        vm.dataId = stateParams.dataId;
        vm.fields = [
            {name: "Name", value: "name", table: true},
            {name: "Email", value: "email", table: true},
            {name: "Phone", value: "phone", table: true},
            {name: "Subject", value: "subject", table: true},
            {name: "Message", value: "message", table: false}
        ];
    }
})()