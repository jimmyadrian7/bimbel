(() => {
    "use strict";

    angular.module('app.module.laporan.tabungan_aset_report')
        .controller('TabunganAsetReportController', TabunganAsetReportController);

    TabunganAsetReportController.$inject = ['req', '$element', 'moment', 'guruOpt', 'logger', 'kursusOpt'];

    function TabunganAsetReportController(req, $element, moment, guruOpt, logger, kursusOpt)
    {
        let vm = this;

        vm.myPdf = true;
        vm.generate = generate;
        vm.data = {
            start_date: moment(new Date()).format("YYYY-MM"),
        };

        vm.fields = [
            {type: 'month', value: 'start_date'},
            {type: 'selection', selection: kursusOpt, value: 'tempat_kursus'},
        ];

        function generate()
        {
            req.post('generate/report/tabungan_aset', vm.data).then(response => {
                vm.myPdf = false;
                let container = $element[0].querySelector("#myPdf");
                container.setAttribute('src', `data:application/pdf;base64, ${response.data}#page=1&zoom=80`);
            });
        }
    }
})()