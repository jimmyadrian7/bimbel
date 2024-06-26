(() => {
    "use strict";

    angular.module('app.module.laporan.gaji_guru')
        .controller('GajiGuruController', GajiGuruController);

    GajiGuruController.$inject = ['req', '$element', 'moment', 'kursusOpt'];

    function GajiGuruController(req, $element, moment, kursusOpt)
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
            req.post('generate/report/gaji', vm.data).then(response => {
                vm.myPdf = false;
                let container = $element[0].querySelector("#myPdf");
                container.setAttribute('src', `data:application/pdf;base64, ${response.data}#page=1&zoom=80`);
            });
        }
    }
})()