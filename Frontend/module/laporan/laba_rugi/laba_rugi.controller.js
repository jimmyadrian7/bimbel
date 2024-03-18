(() => {
    "use strict";

    angular.module('app.module.laporan.laba_rugi')
        .controller('LabaRugiController', LabaRugiController);

    LabaRugiController.$inject = ['req', '$element', 'moment', 'kursusOpt', '$scope'];

    function LabaRugiController(req, $element, moment, kursusOpt, $scope)
    {
        let vm = this;

        vm.myPdf = true;
        vm.generate = generate;
        vm.data = {
            start_date: moment(new Date()).format("YYYY-MM"),
            type: 'month',
            year: moment(new Date()).format('YYYY')
        };

        $scope.$watch(()=> vm.data.type, watchType);

        let typeOpt = [
            {label: 'Bulan', value: 'month'},
            {label: 'Tahun', value: 'year'},
        ];

        vm.fields = [
            {type: 'selection', value: 'type', selection: typeOpt},
            {type: 'month', value: 'start_date', hidden: false},
            {value: 'year', hidden: true},
            {type: 'selection', selection: kursusOpt, value: 'tempat_kursus'}
        ];

        function watchType(newVal, oldVal)
        {
            if (newVal != oldVal)
            {
                if (newVal == 'month')
                {
                    vm.fields[1].hidden = false;
                    vm.fields[2].hidden = true;
                }
                else
                {
                    vm.fields[1].hidden = true;
                    vm.fields[2].hidden = false;
                }
            }
        }

        function generate()
        {
            let url = 'generate/report/labarugi';

            if (vm.data.type == 'year')
            {
                url += '/tahunan';
            }

            req.post(url, vm.data).then(response => {
                vm.myPdf = false;
                let container = $element[0].querySelector("#myPdf");
                container.setAttribute('src', `data:application/pdf;base64, ${response.data}#page=1&zoom=80`);
            });
        }
    }
})()