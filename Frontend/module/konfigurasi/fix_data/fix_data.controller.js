(() => {
    "use strict";

    angular.module('app.module.konfigurasi.fix_data')
        .controller('FixDataController', FixDataController);

    FixDataController.$inject = ['$stateParams', '$compile', '$scope', 'req', 'logger'];

    function FixDataController(stateParams, $compile, $scope, req, logger)
    {
        let vm = this;

        vm.resetKodeTagihan = resetKodeTagihan;

        function resetKodeTagihan()
        {
            req.post('reset/sequance/tagihan').then(resp => {
                logger.success("Berhasil reset sequance Tagihan");
            });
        }
    }
})()