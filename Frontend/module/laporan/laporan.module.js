(() => {
    "use strict";

    angular.module('app.module.laporan', [
        "app.module.laporan.laba_rugi", "app.module.laporan.gaji_guru", "app.module.laporan.pengeluaran",
        "app.module.laporan.deposit", "app.module.laporan.pendapatan", "app.module.laporan.pendapatan_guru",
        "app.module.laporan.iuran", "app.module.laporan.tabungan_aset_report"
    ]);
})()