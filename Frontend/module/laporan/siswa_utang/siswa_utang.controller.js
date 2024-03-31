(() => {
    "use strict";

    angular.module('app.module.laporan.siswa_utang')
        .controller('SiswaUtangController', SiswaUtangController);

    SiswaUtangController.$inject = ['req', '$element', 'moment', 'kursusOpt'];

    function SiswaUtangController(req, $element, moment, kursusOpt)
    {
        let vm = this;

        vm.myPdf = true;
        vm.generate = generate;
        vm.data = {};

        vm.fields = [
            {type: 'selection', selection: kursusOpt, value: 'tempat_kursus'}
        ];

        function generate()
        {
            req.post('generate/report/siswa/utang', vm.data).then(response => {
                vm.myPdf = false;
                let container = $element[0].querySelector("#myPdf");
                container.setAttribute('src', `data:application/pdf;base64, ${response.data}#page=1&zoom=80`);
            });
        }
    }
})()