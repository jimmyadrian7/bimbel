(() => {
    "use strict";

    angular.module('app.module.laporan.pengeluaran')
        .controller('LaporanPengeluaranController', LaporanPengeluaranController);

    LaporanPengeluaranController.$inject = ['req', '$element', 'moment'];

    function LaporanPengeluaranController(req, $element, moment)
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
            req.post('generate/report/pengeluaran', vm.data).then(response => {
                vm.myPdf = false;
                let container = $element[0].querySelector("#myPdf");
                container.setAttribute('src', `data:application/pdf;base64, ${response.data}#page=1&zoom=80`);
            });
        }
    }
})()