<?php
namespace Bimbel\Report\Controller;

use \Bimbel\Report\Controller\BaseReportController;
use Illuminate\Database\Capsule\Manager as DB;
use \Bimbel\Pengeluaran\Model\Gaji;

class ReportController extends BaseReportController
{
    public function queryPendapatan($postData)
    {
        $gaji = new Gaji();
        $pendapatan_iuran = $gaji->queryIuran($postData['start_date'] . "-01");
        $pendapatan_dll = $gaji->queryLainLain($postData['start_date'] . "-01");
        $pendapatan_iuran->unionAll($pendapatan_dll);

        $query = DB::query()
            ->fromSub($pendapatan_iuran, 'tagihan')
            ->select(
                'nama_item AS deskripsi', 
                DB::raw('COUNT(*) AS qty'), 
                DB::raw('SUM(sub_total) AS sub_total'), 
                DB::raw('SUM(potongan) AS potongan'),
                DB::raw('SUM(total_terbagi) AS total')
            )
            ->groupBy('nama_item');

        if (array_key_exists('tempat_kursus', $postData) && !empty($postData['tempat_kursus']))
        {
            $query->where('kursus_id', $postData['tempat_kursus']);
        }
        else
        {
            $session = new \Bimbel\Master\Model\Session();
            if (!$session->isSuperUser())
            {
                $kursus_ids = $session->getKursusIds();
                $query->whereIn('kursus_id', $kursus_ids);
            }
        }

        return $query->get();
    }

    public function queryGajiGuru($postData)
    {
        $gaji = new Gaji();
        $start_date = $postData['start_date'] . "-01";
        $pendapatan_iuran = $gaji->queryIuran($start_date);
        $pendapatan_dll = $gaji->queryLainLain($start_date);
        $pendapatan_iuran->unionAll($pendapatan_dll);
        $kursus_ids = [];

        $query = DB::query()
            ->fromSub($pendapatan_iuran, 'tagihan')
            ->select(DB::raw('"Gaji Guru ' . $this->convertDate($start_date, 'F Y') . '" AS nama'), DB::raw('1 AS jumlah'), DB::raw('SUM(komisi) AS total'));

        if (array_key_exists('tempat_kursus', $postData) && !empty($postData['tempat_kursus']))
        {
            $query->where('kursus_id', $postData['tempat_kursus']);
            $kursus_ids[] = $postData['tempat_kursus'];
        }
        else
        {
            $session = new \Bimbel\Master\Model\Session();
            if (!$session->isSuperUser())
            {
                $kursus_ids = $session->getKursusIds();
                $query->whereIn('kursus_id', $kursus_ids);
            }
        }

        $iuran = $query->get();

        $tunjangan = new \Bimbel\Guru\Model\TunjanganGuru();
        $tunjangan = $tunjangan->whereHas('guru', function($query) use ($kursus_ids) {
            $query->whereHas('kursus', function($q) use ($kursus_ids) {
                $q->whereIn('kursus.id', $kursus_ids);
            });
        })->sum('nominal');

        $iuran[0]->total += $tunjangan;

        return $iuran;
    }

    public function queryPengeluaran($postData)
    {
        $start_date = new \DateTime($postData['start_date'] . "-01");
        $month = $start_date->format('m');
        $year = $start_date->format('Y');

        $pengeluaran = new \Bimbel\Pengeluaran\Model\Pengeluaran();
        $pengeluaran = $pengeluaran
            ->select('nama', 'jumlah', 'total')
            ->whereMonth('tanggal', '=', $month)
            ->whereYear('tanggal', '=', $year);

        if (array_key_exists('tempat_kursus', $postData) && !empty($postData['tempat_kursus']))
        {
            $pengeluaran = $pengeluaran->where('kursus_id', $postData['tempat_kursus']);
        }
        else
        {
            $session = new \Bimbel\Master\Model\Session();
            if (!$session->isSuperUser())
            {
                $kursus_ids = $session->getKursusIds();
                $pengeluaran = $pengeluaran->whereIn('kursus_id', $kursus_ids);
            }
        }

        $gaji_guru = collect($this->queryGajiGuru($postData)->toArray());
        $pengeluaran = collect($pengeluaran->get()->toArray());

        $result = $gaji_guru->merge($pengeluaran);

        return $result;
    }

    public function getLabaRugi($request, $args, &$response)
    {
        $result = [];

        try
        {
            $postData = $request->getParsedBody();

            $tagihan = $this->queryPendapatan($postData);
            $pengeluaran = $this->queryPengeluaran($postData);

            $data = [
                'judul' => "Laba Rugi",
                'pendapatans' => $tagihan,
                'total_qty' => $tagihan->sum('qty'),
                'total_pendapatan' => $tagihan->sum('total'),
                'total_sub_total' => $tagihan->sum('sub_total'),
                'total_potongan' => $tagihan->sum('potongan'),
                'pengeluarans' => $pengeluaran,
                'total_pengeluaran' => $pengeluaran->sum('total'),
                'total_laba' => $tagihan->sum('total') - $pengeluaran->sum('total'),
                'periode' => $this->convertDate($postData['start_date'] . "-01", 'F Y')
            ];

            $result = $this->toPdf("Report/View/labarugi.twig", $data);
        }
        catch(\Error $e)
        {
            $result = $this->container->get('error')($e, $response);
        }

        return $result;
    }

    public function getGaji($request, $args, &$response)
    {
        $result = [];
        
        try
        {
            $session = new \Bimbel\Master\Model\Session();
            $postData = $request->getParsedBody();
            $gaji = new Gaji();

            // $start_date = explode('-', $postData['start_date']);
            // $year = $start_date[0];
            // $month = $start_date[1];
            $date = new \DateTime($postData['start_date'] . "-01");
            $date->modify("+1 month");
            $year = $date->format("Y");
            $month = $date->format("m");

            $gaji = $gaji
                ->whereMonth('tanggal', $month)
                ->whereYear('tanggal', $year);

            if (array_key_exists('tempat_kursus', $postData) && !empty($postData['tempat_kursus']))
            {
                $gaji = $gaji->whereHas('guru', function($q) use ($postData) {
                    $q->whereHas('kursus', function ($qq) use ($postData) {
                        $qq->where('kursus.id', $postData['tempat_kursus']);
                    });
                });
            }
            else
            {
                if (!$session->isSuperUser())
                {
                    $kursus_ids = $session->getKursusIds();
                    $gaji = $gaji->whereHas('guru', function($q) use ($kursus_ids) {
                        $q->whereHas('kursus', function ($qq) use ($kursus_ids) {
                            $qq->whereIn('kursus.id', $kursus_ids);
                        });
                    });
                }
            }

            $gaji = $gaji->get();

            $data = [
                'judul' => "Gaji Guru",
                'gajis' => $gaji,
                'total_gaji' => $gaji->sum('komisi'),
                'periode' => $this->convertDate($postData['start_date'] . "-01", "F Y")
            ];

            $result = $this->toPdf("Report/View/gaji.twig", $data);
        }
        catch(\Error $e)
        {
            $result = $this->container->get('error')($e, $response);
        }

        return $result;
    }

    public function getPendapatan($request, $args, &$response)
    {
        $result = [];

        try
        {
            $postData = $request->getParsedBody();

            $tagihan = $this->queryPendapatan($postData);

            $data = [
                'judul' => "Pendapatan",
                'pendapatans' => $tagihan,
                'total_qty' => $tagihan->sum('qty'),
                'total_pendapatan' => $tagihan->sum('total'),
                'total_sub_total' => $tagihan->sum('sub_total'),
                'total_potongan' => $tagihan->sum('potongan'),
                'periode' => $this->convertDate($postData['start_date'] . '-01', 'F Y')
            ];

            $result = $this->toPdf("Report/View/pendapatan.twig", $data);
        }
        catch(\Error $e)
        {
            $result = $this->container->get('error')($e, $response);
        }

        return $result;
    }
    
    public function getPengeluaran($request, $args, &$response)
    {
        $result = [];

        try
        {
            $postData = $request->getParsedBody();
            $pengeluaran = $this->queryPengeluaran($postData);

            $data = [
                'judul' => "Pengeluaran",
                'pengeluarans' => $pengeluaran,
                'total_pengeluaran' => $pengeluaran->sum('total'),
                'periode' => $this->convertDate($postData['start_date'] . "-01", "F Y")
            ];

            $result = $this->toPdf("Report/View/pengeluaran.twig", $data);
        }
        catch(\Error $e)
        {
            $result = $this->container->get('error')($e, $response);
        }

        return $result;
    }

    public function getDeposit($request, $args, &$response)
    {
        $result = [];
        
        try
        {
            $postData = $request->getParsedBody();
            // $start_date = explode("-", $postData['start_date']);
            // $year = $start_date[0];
            // $month = $start_date[1];

            $deposit = new \Bimbel\Siswa\Model\Deposit();
            $status_deposit_list = [];
            foreach ($deposit->status_enum as $key => $value) {
                $status_deposit_list[$value['value']] = $value['label'];
            }

            // $deposit = $deposit
            //     ->whereYear('tanggal', '=', $year)
            //     ->whereMonth('tanggal', '=', $month)
            //     ->where('status', 'a');

            $deposit = $deposit->whereRaw(DB::raw('1 = 1'));

            if (array_key_exists('tempat_kursus', $postData) && !empty($postData['tempat_kursus']))
            {
                $deposit = $deposit->whereHas('siswa', function($q) use ($postData) {
                    $q->where('kursus_id', $postData['tempat_kursus']);
                });
            }
            else
            {
                $session = new \Bimbel\Master\Model\Session();
                if (!$session->isSuperUser())
                {
                    $kursus_ids = $session->getKursusIds();
                    $deposit = $deposit->whereHas('siswa', function($q) use ($kursus_ids) {
                        $q->whereIn('kursus_id', $kursus_ids);
                    });
                }
            }

            $deposit = $deposit->orderBy('tanggal', 'DESC')->get();

            $data = [
                'judul' => "Deposit",
                'deposits' => $deposit,
                'total' => $deposit->sum('nominal'),
                // 'periode' => $this->convertDate($postData['start_date'] . "-01"),
                'status_deposit_list' => $status_deposit_list
            ];

            $result = $this->toPdf("Report/View/deposit.twig", $data);
        }
        catch(\Error $e)
        {
            $result = $this->container->get('error')($e, $response);
        }

        return $result;
    }
}