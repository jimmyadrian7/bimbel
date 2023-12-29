(() => {
    "use strict";

    angular.module('app.module.laporan.laba_rugi')
        .controller('LabaRugiController', LabaRugiController);

    LabaRugiController.$inject = ['req', '$element', 'moment', 'kursusOpt'];

    function LabaRugiController(req, $element, moment, kursusOpt)
    {
        let vm = this;

        vm.myPdf = true;
        vm.generate = generate;
        vm.data = {
            start_date: moment(new Date()).format("YYYY-MM")
        };

        vm.fields = [
            {type: 'month', value: 'start_date'},
            {type: 'selection', selection: kursusOpt, value: 'tempat_kursus'}
        ];

        function generate()
        {
            req.post('generate/report/labarugi', vm.data).then(response => {
                vm.myPdf = false;
                let container = $element[0].querySelector("#myPdf");
                container.setAttribute('src', `data:application/pdf;base64, ${response.data}#page=1&zoom=80`);
            });
        }
    }
})()