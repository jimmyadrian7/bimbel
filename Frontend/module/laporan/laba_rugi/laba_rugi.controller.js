(() => {
    "use strict";

    angular.module('app.module.laporan.laba_rugi')
        .controller('LabaRugiController', LabaRugiController);

    LabaRugiController.$inject = ['req', '$element', 'moment'];

    function LabaRugiController(req, $element, moment)
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
            {type: 'date', value: 'end_date'}
        ];

        function generate()
        {
            req.post('generate/report/pendapatan', vm.data).then(response => {
                vm.myPdf = false;
                let container = $element[0].querySelector("#myPdf");
                container.setAttribute('src', `data:application/pdf;base64, ${response.data}#page=1&zoom=80`);
            });
        }
    }
})()