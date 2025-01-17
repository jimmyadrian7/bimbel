import modal from "./html/modal/modal.html";
import modalDetail from "./html/modal/modal-detail.html";
import modalDiskon from "./html/modal/modal-diskon.html";
import modalPindahGuru from "./html/modal/modal-pindah.html";
import modalKwitansi from "./html/modal/modal-kwitansi.html";

(() => {
    "use strict";

    angular.module('app.module.pembayaran.tagihan')
        .controller('TagihanController', TagihanController);

    TagihanController.$inject = [
        '$stateParams', '$parse', '$compile', '$scope', 'req', 'Modal', '$state', 'session', 'logger', 'moment'
    ];

    function TagihanController(stateParams, $parse, $compile, $scope, req, Modal, state, session, logger, moment) {
        let vm = this;

        let statusOpt = [
            { label: 'Proses', value: 'p' },
            { label: 'Menunggu Verifikasi', value: 'c' },
            { label: 'Lunas', value: 'l' }
        ];

        let diksonOption = [
            { label: "Persen", value: "p" },
            { label: "Nominal", value: "n" }
        ];

        let jenisOption = [
            { label: "Tunai", value: "c" },
            { label: "Transfer", value: "tf" }
        ];

        vm.isSiswa = session.isSiswa();
        vm.isAdmin = session.isAdminCabang() || session.isSuperUser();
        vm.additionalData = {};

        vm.data = { tagihan_detail: [] };
        vm.status_field = { name: "Status", value: "status", type: "selection", selection: statusOpt, table: true, hidden: true, hideDetail: true };
        vm.dataId = stateParams.dataId;
        vm.activeDetail = -1;
        vm.fields = [
            { name: "Tanggal", value: "tanggal", type: 'date', table: true },
            { name: "Kode", value: "code", table: true, hidden: true },
            {
                name: "Siswa",
                value: "siswa_id",
                type: "autocomplete",
                url: 'siswa/search/autocomplete',
                valueName: 'siswa_data',
                table: true,
                required: true
            },
            { name: "Siswa (mandarin)", value: "siswa.orang.nama_mandarin", table: true, hidden: true },
            { name: "Guru", value: "guru.orang.nama", table: true, hidden: true },
            { name: "Sub Total", value: "sub_total", type: 'number', table: true, hidden: true },
            { name: "Potongan", value: "potongan", type: 'number', table: true, hidden: true },
            { name: "Total", value: "total", type: 'number', table: true, hidden: true },
            { name: "Hutang", value: "hutang", type: 'number', hidden: true },
            vm.status_field
        ];

        vm.tagihanDetailFields = [
            { name: "Nama", value: "nama" },
            { name: "Periode Mulai", value: "tanggal_iuran_mulai", type: 'date' },
            { name: "Periode Akhir", value: "tanggal_iuran_berakhir", type: 'date' },
            { name: "Nominal", value: "nominal", type: 'number' },
            { name: "Qty", value: "qty", type: 'number' },
            { name: "Sub Total", value: "sub_total", type: 'number' },
            { name: "Diskon", value: "potongan", type: 'number' },
            { name: "Total", value: "total", type: 'number' }
        ];

        if (!session.isSiswa()) {
            vm.tagihanDetailFields.push({ name: "Komisi", value: "komisi", type: 'number' });
        }


        vm.diskonFields = [
            { name: "Diskon", value: "diskon", type: 'number' },
            { name: "Tipe diskon", value: "tipe_diskon" }
        ];

        vm.transaksiFields = [
            { name: "Tanggal", value: "tanggal", type: 'date', required: true },
            { name: "Nominal", value: "nominal", type: 'number', required: true },
            { name: "Jenis Pembayaran", value: "jenis_pembayaran", type: 'selection', selection: jenisOption, required: true },
            { name: "Bukti Pembayaran", value: "bukti_pembayaran", type: 'file', required: true },
        ];

        vm.pindahGuruFields = [
            {
                name: "Guru",
                value: "guru_id",
                type: "autocomplete",
                url: 'guru/search/autocomplete',
                valueName: 'guru_data',
                required: true
            },
            { name: "Persen Komisi (%)", value: "komisi", type: 'number', required: true },
        ];

        vm.modal = { form: { tanggal: moment(new Date()).format("YYYY-MM-DD") } };
        vm.myModal = false;
        vm.myModalDiskon = false;
        vm.myModalPindahGuru = false;

        vm.getValue = getValue;
        vm.getLabel = getLabel;

        vm.jenisOption = jenisOption;

        vm.bayarTagihan = bayarTagihan;
        vm.buatTransaksi = buatTransaksi;

        vm.tambahDetail = tambahDetail;
        vm.fetchPembiayaan = fetchPembiayaan;
        vm.isAdded = isAdded;
        vm.addPembiayaan = addPembiayaan;
        vm.removePembiayaan = removePembiayaan;
        vm.updateTotal = updateTotal;
        vm.deleteTagihan = deleteTagihan;

        vm.tambahDiskon = tambahDiskon;
        vm.fetchDiskon = fetchDiskon;
        vm.addDiskon = addDiskon;
        vm.removeDiskon = removeDiskon;

        vm.getDiskonOpt = getDiskonOpt;

        vm.verif = verif;

        vm.generateInvoice = generateInvoice;
        vm.generateKwitansi = generateKwitansi;
        vm.cetakKwitansi = cetakKwitansi;
        vm.editInfoKwitansi = editInfoKwitansi;
        vm.changeProgramBelajar = changeProgramBelajar;
        vm.saveInfoKwitansi = saveInfoKwitansi;

        vm.notify = notify;

        vm.editTransaksi = editTransaksi;
        vm.deleteTransaksi = deleteTransaksi;

        vm.pindahGuru = pindahGuru;
        vm.postPindahGuru = postPindahGuru;

        vm.getNumber = getNumber;

        $scope.$watch(() => vm.data.siswa_id, watchSiswaId);

        function getValue(field) {
            if (!field) {
                return "-";
            }

            let result = $parse(field.value)(vm.data);

            if (field.type == 'selection') {
                result = getLabel(result, field.selection);
            }

            return result || "-";
        }
        function getLabel(val, arr) {
            for (let index = 0; index < arr.length; index++) {
                if (arr[index].value == val) {
                    return arr[index].label;
                }
            }
        }

        function bayarTagihan() {
            vm.modal.form = { tanggal: moment(new Date()).format("YYYY-MM-DD"), jenis_pembayaran: 'tf' };
            vm.myModal = $compile(modal)($scope);
        }
        function buatTransaksi() {
            let data = {
                nominal: vm.modal.form.nominal,
                tagihan_id: vm.data.id,
                bukti_pembayaran: vm.modal.form.bukti_pembayaran,
                tanggal: vm.modal.form.tanggal,
                jenis_pembayaran: vm.modal.form.jenis_pembayaran
            };

            let isValid = true;
            let invalid = [];

            if (!data.nominal) {
                invalid.push("Nominal wajib diisi.");
            }
            if (data.jenis_pembayaran == 'tf' && !data.bukti_pembayaran) {
                invalid.push("Bukti Pembayaran wajib diisi.");

            }
            if (!data.tanggal) {
                invalid.push("Tanggal wajib diisi.");
            }
            if (!data.jenis_pembayaran) {
                invalid.push("Jenis Pembayaran wajib diisi.");
            }

            if (invalid.length > 0) {
                logger.error(invalid[0]);
                return;
            }

            if (vm.modal.form.id) {
                data.id = vm.modal.form.id;

                req.put('transaksi', data).then(response => {
                    Modal.getInstance(vm.myModal[0]).hide();
                    state.reload();
                });
            }
            else {
                req.post('transaksi', data).then(response => {
                    Modal.getInstance(vm.myModal[0]).hide();
                    state.reload();
                });
            }
        }


        function tambahDetail() {
            vm.myModal = $compile(modalDetail)($scope);
        }
        function fetchPembiayaan() {
            let filter_data = {
                filter: [
                    {
                        field: 'kategori_pembiayaan',
                        operation: '!=',
                        value: 's'
                    }
                ]
            };
            req.post('pembiayaans/custom', filter_data).then(data => {
                vm.modal.pembiayaan = data;
            });
        }
        function isAdded(pembiayaan) {
            let result = false;

            for (let index = 0; index < vm.data.tagihan_detail.length; index++) {
                const s = vm.data.tagihan_detail[index];

                if (s.pembiayaan_id == pembiayaan.id) {
                    result = true;
                    break;
                }
            }

            return result;
        }
        function addPembiayaan(pembiayaan) {
            vm.data.tagihan_detail.push({
                kode: pembiayaan.kode,
                nama: pembiayaan.nama,
                kategori_pembiayaan: pembiayaan.kategori_pembiayaan,
                kategori_pembiayaan_id: pembiayaan.kategori_pembiayaan_id,
                nominal: pembiayaan.harga,
                sub_total: pembiayaan.harga,
                qty: 1,
                total: pembiayaan.harga,
                pembiayaan: pembiayaan,
                pembiayaan_id: pembiayaan.id
            });
        }
        function removePembiayaan(idx) {
            vm.data.tagihan_detail.splice(idx, 1);
        }
        function updateTotal(idx) {
            let tagihan_detail = vm.data.tagihan_detail[idx];
            vm.data.tagihan_detail[idx].sub_total = tagihan_detail.qty * tagihan_detail.nominal;
        }
        function deleteTagihan(idx) {
            removePembiayaan(idx);
            let data = {
                id: vm.data.id,
                tagihan_detail: vm.data.tagihan_detail,
                diskon: vm.data.diskon
            };
            req.put('tagihan', data).then(response => state.reload());
        }

        function tambahDiskon(idx) {
            vm.activeDetail = idx;
            vm.myModalDiskon = $compile(modalDiskon)($scope);
        }
        function fetchDiskon() {
            req.get('diskons').then(data => {
                vm.modal.diskon = data;
            });
        }
        function addDiskon(diskon) {
            vm.data.tagihan_detail[vm.activeDetail].diskon = diskon;
            vm.data.tagihan_detail[vm.activeDetail].diskon_id = diskon.id;

            let total_diskon = 0;
            let total = vm.data.tagihan_detail[vm.activeDetail].sub_total;

            if (diskon.tipe_diskon == 'n') {
                total_diskon = diskon.diskon;
            }
            else {
                total_diskon = (total * diskon.diskon / 100);
            }

            vm.data.tagihan_detail[vm.activeDetail].total = total - total_diskon;

            Modal.getInstance(vm.myModalDiskon[0]).hide();
        }
        function removeDiskon(idx) {
            delete vm.data.tagihan_detail[idx].diskon;
            delete vm.data.tagihan_detail[idx].diskon_id;

            vm.data.tagihan_detail[idx].total = vm.data.tagihan_detail[idx].sub_total;
        }

        function getDiskonOpt(val) {
            for (let index = 0; index < diksonOption.length; index++) {
                if (diksonOption[index].value == val) {
                    return diksonOption[index].label;
                }
            }
        }

        function verif(transaksi) {
            let data = {
                id: transaksi.id,
                status: 'v'
            };
            req.put('transaksi', data).then(response => state.reload());
        }

        function generateInvoice() {
            req.get(`generate/report/invoice/${vm.dataId}`).then(response => {
                vm.activePdf = { filename: "invoice.pdf", filetype: 'application/pdf', base64: response.data };
                let element = `<app-modal-preview value='vm.activePdf'></app-modal-preview>`;
                element = $compile(element)($scope);
            });
        }

        function generateKwitansi() {
            // vm.modal.form = { tanggal: moment(new Date()).format("YYYY-MM-DD"), jenis_pembayaran: 'tf' };
            vm.myModal = $compile(modalKwitansi)($scope);
        }

        function cetakKwitansi() {
            req.get(`generate/report/kwitansi/${vm.dataId}`).then(response => {
                vm.activePdf = { filename: "invoice.pdf", filetype: 'application/pdf', base64: response.data };
                let element = `<app-modal-preview value='vm.activePdf'></app-modal-preview>`;
                element = $compile(element)($scope);
            });
        }

        function editInfoKwitansi() {
            $('.editMode').removeClass('d-none');
            $('.editEl').hide();

            if (vm.data.program_belajar_other) {
                vm.data.program_belajar_other = vm.data.program_belajar;
                vm.data.program_belajar = 'other';

            }
        }

        function changeProgramBelajar(selected) {
            var ispogram = ['Mandarin', 'Inggris', 'Bimbel', 'Calistung'];
            if (!ispogram.includes(selected)) {
                vm.data.program_belajar = 'other';
            }
            else {
                vm.data.program_belajar_other = '';
            }
        }

        function saveInfoKwitansi() {
            if (vm.data.program_belajar_other) {
                vm.data.program_belajar = vm.data.program_belajar_other;
            }

            req.put('tagihan', vm.data).then(response => {
                $('.editMode').addClass('d-none');
                $('.editEl').show();
            });
        }

        function notify() {
            req.get(`notify/tagihan/wa/${vm.dataId}`).then(response => {
                if (response) {
                    logger.success("Notifikasi telah terkirim");
                }
            });
        }


        function editTransaksi(d) {
            vm.modal.form = d;
            vm.myModal = $compile(modal)($scope);
        }
        function deleteTransaksi(d) {
            let data = { id: d.id };
            req.del('transaksi', data).then(response => {
                if (response) {
                    logger.success("Success");
                    state.reload();
                }
            });
        }


        function pindahGuru() {
            vm.myModalPindahGuru = $compile(modalPindahGuru)($scope);
        }

        function postPindahGuru() {
            let data = { tagihan_id: vm.data.id, guru_id: vm.modal.form.guru_id, komisi: vm.modal.form.komisi };
            req.post('tagihan/ganti/guru', data).then(response => {
                Modal.getInstance(vm.myModalPindahGuru[0]).hide();
                state.reload();
            });
        }

        function getNumber(field, data) {
            let result = data[field.value];

            if (field.value == 'diskon.diskon') {
                let diskon = data.diskon;

                if (diskon) {
                    if (diskon.tipe_diskon == 'n') {
                        result = diskon.diskon;
                    }
                    else {
                        result = data.sub_total * diskon.diskon / 100;
                    }
                }
            }

            if (field.value == 'komisi') {
                // if (!result)
                // {
                let jenis_komisi = data.pembiayaan.jenis_komisi;

                switch (jenis_komisi) {
                    case 's':
                        if (vm.additionalData.komisi_siswa) {
                            result = data.total * vm.additionalData.komisi_siswa / 100;
                        }
                        else {
                            result = 0;
                        }
                        break;
                    case 'p':
                        result = data.total * data.pembiayaan.nominal / 100;
                        break;
                    case 'n':
                        result = data.pembiayaan.nominal || 0;
                        break;

                    default:
                        result = 0;
                        break;
                }

                if (result != 0) {
                    let iuran_mulai = data.tanggal_iuran_mulai;
                    let iuran_berakhir = data.tanggal_iuran_berakhir;
                    let total_bulan = moment(new Date(iuran_berakhir)).diff(new Date(iuran_mulai), 'months', true);

                    result = result / (total_bulan + 1);
                }
                // }
            }

            return result;
        }

        function watchSiswaId() {
            let el = $('[name="vm.data.siswa_id"]');

            if (el.length > 0) {
                let el_data = el.select2('data');
                if (el_data.length > 0) {
                    if (!el_data[0].komisi) {
                        vm.additionalData.komisi_siswa = el.find(':selected').next().attr('komisi');
                    }
                    else {
                        vm.additionalData.komisi_siswa = el_data[0].komisi;
                    }
                }
            }
        }
    }
})()