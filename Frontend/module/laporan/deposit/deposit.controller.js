(() => {
    "use strict";

    angular.module('app.module.laporan.deposit')
        .controller('LaporanDepositController', LaporanDepositController);

    LaporanDepositController.$inject = ['req', '$element', 'moment', 'kursusOpt'];

    function LaporanDepositController(req, $element, moment, kursusOpt)
    {
        let vm = this;
        let statusOpt = [
            {label: "All", value: ""},
            {label: "Aktif", value: "a"},
            {label: "Terima", value: "t"},
            {label: "Hangus", value: "h"}
        ];

        vm.myPdf = true;
        vm.generate = generate;
        vm.data = {
            start_date: moment(new Date()).format("YYYY-MM")
        };

        vm.fields = [
            {type: 'selection', selection: statusOpt, value: 'status'},
            {type: 'selection', selection: kursusOpt, value: 'tempat_kursus'}
        ];

        function generate()
        {
            req.post('generate/report/deposit', vm.data).then(response => {
                vm.myPdf = false;
                let container = $element[0].querySelector("#myPdf");
                container.setAttribute('src', `data:application/pdf;base64, ${response.data}#page=1&zoom=80`);
            });
        }
    }
})()