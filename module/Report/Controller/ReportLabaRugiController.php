<?php
namespace Bimbel\Report\Controller;

use \Bimbel\Report\Controller\BaseReportController;
use Illuminate\Database\Capsule\Manager as DB;
use \Bimbel\Pengeluaran\Model\Gaji;
use \Bimbel\Pembayaran\Model\TagihanDetail;

class ReportLabaRugiController extends BaseReportController
{
    public function getKursusIds($postData)
    {
        $kursus_ids = [];
        
        if (array_key_exists('tempat_kursus', $postData))
        {
            $kursus_ids = [$postData['tempat_kursus']];
        }
        else
        {
            $session = new \Bimbel\Master\Model\Session();
            if (!$session->isSuperUser())
            {
                $kursus_ids = $session->getKursusIds();
            }
        }

        return $kursus_ids;
    }

    public function getPendapatanIuran($year, $kursus_ids)
    {
        $query = DB::table('tagihan_detail');
        $start_year = $year . "-01-01";
        $end_year = $year . "-12-31";

        $query = $query
            ->select(
                'tagihan_detail.nama AS deskripsi', 'tagihan_detail.qty', 'tagihan_detail.potongan' , 'tagihan_detail.sub_total', 'tagihan_detail.total',
                DB::raw('
                    CASE
                        WHEN YEAR(tagihan_detail.tanggal_iuran_mulai) < "' . $year . '" THEN
                            TIMESTAMPDIFF(MONTH, "' . $start_year . '", tagihan_detail.tanggal_iuran_berakhir)
                        WHEN YEAR(tagihan_detail.tanggal_iuran_berakhir) > "' . $year . '" THEN
                            TIMESTAMPDIFF(MONTH, tagihan_detail.tanggal_iuran_mulai, "' . $end_year . '")
                        ELSE
                            tagihan_detail.bulan
                    END * komisi
                    AS komisi
                ')
            )
            ->join('tagihan', 'tagihan.id', 'tagihan_detail.tagihan_id')
            ->where('tagihan_detail.system', true)
            ->where('tagihan_detail.kategori_pembiayaan', 's')
            ->where(function($q) use ($year) {
                $q
                    ->whereYear('tagihan_detail.tanggal_iuran_mulai', $year)
                    ->orWhereYear('tagihan_detail.tanggal_iuran_berakhir', $year);
            })
            ->whereYear('tagihan.tanggal_lunas', '<=', $year);

        if (count($kursus_ids) > 0)
        {
            $query->whereIn('kursus_id', $kursus_ids);
        }

        $query = DB::query()
            ->fromSub($query, 'tagihan_detail')
            ->select(
                'deskripsi', DB::raw('SUM(qty) AS qty'), DB::raw('SUM(potongan) AS potongan'),
                DB::raw('SUM(tagihan_detail.sub_total) as sub_total'),
                DB::raw('SUM(tagihan_detail.total) - SUM(komisi) as total'),
                DB::raw('SUM(komisi) as komisi')
            )
            ->groupBy('deskripsi');

        return $query;
    }

    public function getPendapatanDll($year, $kursus_ids)
    {
        $query = DB::table('tagihan_detail');
        $start_year = $year . "-01-01";
        $end_year = $year . "-12-31";

        $query = $query
            ->select(
                'tagihan_detail.nama AS deskripsi', 'tagihan_detail.qty', 'tagihan_detail.potongan' , 'tagihan_detail.sub_total', 'tagihan_detail.total',
                'tagihan_detail.komisi'
            )
            ->join('tagihan', 'tagihan.id', 'tagihan_detail.tagihan_id')
            ->where('tagihan_detail.kategori_pembiayaan', '<>', 's')
            ->whereYear('tagihan.tanggal_lunas', $year);

        if (count($kursus_ids) > 0)
        {
            $query->whereIn('kursus_id', $kursus_ids);
        }

        $query = DB::query()
            ->fromSub($query, 'tagihan_detail')
            ->select(
                'deskripsi', DB::raw('SUM(qty) AS qty'), DB::raw('SUM(potongan) AS potongan'),
                DB::raw('SUM(tagihan_detail.sub_total) as sub_total'),
                DB::raw('SUM(tagihan_detail.total) - SUM(komisi) as total'),
                DB::raw('SUM(komisi) as komisi')
            )
            ->groupBy('deskripsi');

        return $query;
    }

    public function queryPenarikan($year, $kursus_ids)
    {
        $penarikan = new \Bimbel\Pengeluaran\Model\Penarikan();
        $penarikan = $penarikan
            ->select(
                'tabungan_aset_id', 
                DB::raw('CONCAT("Penarikan ", tabungan_aset.nama, " ' . $year . '") AS deskripsi'),
                DB::raw('1 AS qty'),
                DB::raw('SUM(penarikan.nominal) AS sub_total'),
                DB::raw('0 AS potongan'),
                DB::raw('SUM(penarikan.nominal) as total')
            )
            ->join('tabungan_aset', 'tabungan_aset.id', 'penarikan.tabungan_aset_id')
            ->whereYear('penarikan.tanggal', $year)
            ->where('penarikan.status', 's');

            if (count($kursus_ids) > 0)
            {
                $penarikan->whereIn('tabungan_aset.kursus_id', $kursus_ids);
            }

        $penarikan->groupBy('tabungan_aset_id');
        return $penarikan->get();
    }

    public function queryPenarikanModal($year, $kursus_ids)
    {
        $penarikan = new \Bimbel\Pengeluaran\Model\TarikModal();

        $penarikan = $penarikan
            ->select(
                'modal_id', 
                DB::raw('CONCAT("Penarikan Modal ", kursus.nama, " ' . $year . '") AS deskripsi'),
                DB::raw('1 AS qty'),
                DB::raw('SUM(tarik_modal.nominal) AS sub_total'),
                DB::raw('0 AS potongan'),
                DB::raw('SUM(tarik_modal.nominal) as total')
            )
            ->join('modal', 'modal.id', 'tarik_modal.modal_id')
            ->join('kursus', 'kursus.id', 'modal.kursus_id')
            ->whereYear('tarik_modal.tanggal', $year)
            ->where('tarik_modal.status', 's');


        if (count($kursus_ids) > 0)
        {
            $penarikan->whereIn('modal.kursus_id', $kursus_ids);
        }

        $penarikan->groupBy('modal_id');
        return $penarikan->get();
    }

    public function queryGajiGuru($year, $kursus_ids)
    {
        $pendapatan_iuran = $this->getPendapatanIuran($year, $kursus_ids);
        $pendapatan_dll = $this->getPendapatanDll($year, $kursus_ids);
        $pendapatan_iuran->unionAll($pendapatan_dll);
        $kursus_ids = [];

        $query = DB::query()
            ->fromSub($pendapatan_iuran, 'tagihan')
            ->select(DB::raw('"Gaji Guru ' . $year . '" AS nama'), DB::raw('1 AS jumlah'), DB::raw('SUM(komisi) AS total'));

        if (count($kursus_ids) > 0)
        {
            $query->whereIn('kursus_id', $kursus_ids);
        }

        $iuran = $query->get();

        $tunjangan = new \Bimbel\Guru\Model\TunjanganGuru();
        $tunjangan = $tunjangan->whereHas('guru', function($query) use ($kursus_ids) {
            $query->whereHas('kursus', function($q) use ($kursus_ids) {
                $q->whereIn('kursus.id', $kursus_ids);
            });
        })->sum('nominal');

        $iuran[0]->total += ($tunjangan * 12);

        return $iuran;
    }

    public function queryPengeluaran($year, $kursus_ids)
    {
        $pengeluaran = new \Bimbel\Pengeluaran\Model\Pengeluaran();
        $pengeluaran = $pengeluaran
            ->select('nama', 'jumlah', 'total')
            ->whereYear('tanggal', '=', $year);

        $deposit = new \Bimbel\Siswa\Model\Deposit();
        $deposit = $deposit
            ->select(DB::raw('"Deposit ' . $year . '" AS nama'), DB::raw('COUNT(*) AS jumlah'), DB::raw('SUM(nominal) AS total'))
            ->whereYear('tanggal_keluar', $year)
            ->groupBy(DB::raw('YEAR(tanggal_keluar)'));


        if (count($kursus_ids) > 0)
        {
            $deposit = $deposit->whereHas('siswa', function($query) use ($kursus_ids) {
                $query->whereIn('kursus_id', $kursus_ids);
            });
        }

        $gaji_guru = collect($this->queryGajiGuru($year, $kursus_ids)->toArray());
        $pengeluaran = collect($pengeluaran->get()->toArray());
        $deposit = collect($deposit->get()->toArray());

        $result = $gaji_guru->merge($pengeluaran)->merge($deposit);

        return $result;
    }

    public function getTahunan($request, $args, &$response)
    {
        $result = [];

        try
        {
            $postData = $request->getParsedBody();

            $tagihanIuran = $this->getPendapatanIuran($postData['year'], $this->getKursusIds($postData))->get();
            $tagihanDll = $this->getPendapatanDll($postData['year'], $this->getKursusIds($postData))->get();
            $tagihanPenarikan = $this->queryPenarikan($postData['year'], $this->getKursusIds($postData));
            $tagihanPenarikanModal = $this->queryPenarikanModal($postData['year'], $this->getKursusIds($postData));
            $pengeluaran = $this->queryPengeluaran($postData['year'], $this->getKursusIds($postData));

            $dataPendapatanPage = [];
            $totalDataPendapatanPage = [];
            $total_laba = 0;

            $dataPendapatanPage['Pendapatan Iuran'] = $tagihanIuran;
            $totalDataPendapatanPage['Pendapatan Iuran'] = [];
            $totalDataPendapatanPage['Pendapatan Iuran']['qty'] = $tagihanIuran->sum('qty');
            $totalDataPendapatanPage['Pendapatan Iuran']['total'] = $tagihanIuran->sum('total');
            $totalDataPendapatanPage['Pendapatan Iuran']['sub_total'] = $tagihanIuran->sum('sub_total');
            $totalDataPendapatanPage['Pendapatan Iuran']['potongan'] = $tagihanIuran->sum('potongan');
            $total_laba += $tagihanIuran->sum('total');

            $dataPendapatanPage['Pendapatan Penjualan'] = $tagihanDll;
            $totalDataPendapatanPage['Pendapatan Penjualan'] = [];
            $totalDataPendapatanPage['Pendapatan Penjualan']['qty'] = $tagihanDll->sum('qty');
            $totalDataPendapatanPage['Pendapatan Penjualan']['total'] = $tagihanDll->sum('total');
            $totalDataPendapatanPage['Pendapatan Penjualan']['sub_total'] = $tagihanDll->sum('sub_total');
            $totalDataPendapatanPage['Pendapatan Penjualan']['potongan'] = $tagihanDll->sum('potongan');
            $total_laba += $tagihanDll->sum('total');

            $dataPendapatanPage['Penarikan Tabungan Aset'] = $tagihanPenarikan;
            $totalDataPendapatanPage['Penarikan Tabungan Aset'] = [];
            $totalDataPendapatanPage['Penarikan Tabungan qty']['total'] = $tagihanPenarikan->sum('qty');
            $totalDataPendapatanPage['Penarikan Tabungan Aset']['total'] = $tagihanPenarikan->sum('total');
            $totalDataPendapatanPage['Penarikan Tabungan Aset']['sub_total'] = $tagihanPenarikan->sum('sub_total');
            $totalDataPendapatanPage['Penarikan Tabungan Aset']['potongan'] = $tagihanPenarikan->sum('potongan');
            $total_laba += $tagihanPenarikan->sum('total');

            $dataPendapatanPage['Penarikan Modal'] = $tagihanPenarikanModal;
            $totalDataPendapatanPage['Penarikan Modal'] = [];
            $totalDataPendapatanPage['Penarikan Modal']['qty'] = $tagihanPenarikanModal->sum('qty');
            $totalDataPendapatanPage['Penarikan Modal']['total'] = $tagihanPenarikanModal->sum('total');
            $totalDataPendapatanPage['Penarikan Modal']['sub_total'] = $tagihanPenarikanModal->sum('sub_total');
            $totalDataPendapatanPage['Penarikan Modal']['potongan'] = $tagihanPenarikanModal->sum('potongan');
            $total_laba += $tagihanPenarikanModal->sum('total');


            $data = [
                'judul' => "Laba Rugi",
                'data_pendapatans' => $dataPendapatanPage,
                'total_pendapatans' => $totalDataPendapatanPage,
                'pengeluarans' => $pengeluaran,
                'total_pengeluaran' => $pengeluaran->sum('total'),
                'total_laba' => $total_laba - $pengeluaran->sum('total'),
                'periode' => $postData['year']
            ];

            $result = $this->toPdf("Report/View/labarugi.twig", $data);
        }
        catch(\Error $e)
        {
            $result = $this->container->get('error')($e, $response);
        }

        return $result;
    }

}