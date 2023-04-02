(() => {
    "use strict";

    angular.module('app.module.laporan.pendapatan_guru')
        .controller('PendapatanGuruController', PendapatanGuruController);

    PendapatanGuruController.$inject = ['req', '$element', 'moment', 'guruOpt', 'logger'];

    function PendapatanGuruController(req, $element, moment, guruOpt, logger)
    {
        let vm = this;

        vm.myPdf = true;
        vm.generate = generate;
        vm.data = {
            start_date: moment(new Date()).format("YYYY-MM-DD"),
            end_date: moment(new Date()).format("YYYY-MM-DD")
        };

        vm.fields = [
            {type: 'date', value: 'start_date'},
            {type: 'selection', selection: guruOpt, value: 'guru_id'}
        ];

        function generate()
        {
            if (!vm.data.guru_id)
            {
                logger.error("Harap pilih guru");
                return;
            }

            req.post('generate/report/pendapatan/guru', vm.data).then(response => {
                vm.myPdf = false;
                let container = $element[0].querySelector("#myPdf");
                container.setAttribute('src', `data:application/pdf;base64, ${response.data}#page=1&zoom=80`);
            });
        }
    }
})()