(() => {
    "use strict";

    angular.module('app.module.konfigurasi.account_configuration')
        .controller('AccountConfigurationController', AccountConfiguration);

    AccountConfiguration.$inject = ['$stateParams'];

    function AccountConfiguration(stateParams)
    {
        let vm = this;

        vm.dataId = stateParams.dataId;
        vm.fields = [];

        vm.wa_fields = [
            [
                {name: "Invoice Template", value: "wa_invoice_template"},
                {name: "Invoice Template Language", value: "wa_invoice_template_language"},
                {name: "Business Account ID", value: "wa_business_account_id"},
            ],
            [
                {name: "Phone Number ID", value: "wa_phone_number_id"},
                {name: "Access Token", value: "wa_access_token"}
            ]
        ];

        vm.mail_fields = [
            [
                {name: "Host", value: "mail_host"},
                {name: "Port", value: "mail_port"}
            ],
            [
                {name: "Username", value: "mail_user"},
                {name: "Password", value: "mail_pass"}
            ]
        ];
    }
})()