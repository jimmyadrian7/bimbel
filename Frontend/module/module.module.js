(() => {
    "use strict";

    angular.module('app.module', [
        'app.core',
        'app.module.profile',
        'app.module.pembayaran',
        'app.module.laporan',
        'app.module.guru',
        'app.module.siswa',
        'app.module.pengeluaran',
        'app.module.web',
        'app.module.konfigurasi',
        'app.module.message',
        'app.module.log',
    ]);
})()