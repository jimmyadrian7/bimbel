(() => {
    "use strict";

    angular.module('app.module.pembayaran', [
        'app.module.pembayaran.pembiayaan',
        'app.module.pembayaran.diskon',
        'app.module.pembayaran.tagihan',
        'app.module.pembayaran.iuran'
    ]);
})()