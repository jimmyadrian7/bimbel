<?php
namespace Bimbel\Report\Controller;

use \Bimbel\Report\Controller\BaseReportController;
use Illuminate\Database\Capsule\Manager as DB;

class ReportController extends BaseReportController
{
    public function queryPendapatan($postData)
    {
        $params = [
            'start_date' => $postData['start_date'],
            'end_date' => $postData['end_date']
        ];
        $query = "
            SELECT 
                tagihan_detail.nama AS deskripsi,
                SUM(tagihan_detail.qty) AS qty,
                sum(tagihan_detail.total - tagihan_detail.komisi) AS total
            FROM tagihan_detail
            LEFT JOIN tagihan ON tagihan.id = tagihan_detail.tagihan_id
            WHERE 
                DATE(tagihan.tanggal) >= :start_date AND 
                DATE(tagihan.tanggal) <= :end_date AND
                %s
            GROUP BY tagihan_detail.nama
        ";
        $where_condition = "1=1";

        if (array_key_exists('tempat_kursus', $postData) && !empty($postData['tempat_kursus']))
        {
            $where_condition = "tagihan.kursus_id = :tempat_kursus";
            $params['tempat_kursus'] = $postData['tempat_kursus'];
        }

        $query = sprintf($query, $where_condition);
        $tagihan = DB::select(DB::raw($query), $params);

        return $tagihan;
    }

    public function queryPengeluaran($postData)
    {
        $pengeluaran = new \Bimbel\Pengeluaran\Model\Pengeluaran();
        $pengeluaran = $pengeluaran
            ->whereDate('tanggal', '>=', $postData['start_date'])
            ->whereDate('tanggal', '<=', $postData['end_date']);

        if (array_key_exists('tempat_kursus', $postData) && !empty($postData['tempat_kursus']))
        {
            $pengeluaran = $pengeluaran->where('kursus_id', $postData['tempat_kursus']);
        }

        $pengeluaran = $pengeluaran->get();

        return $pengeluaran;
    }

    public function getLabaRugi($request, $args)
    {
        $postData = $request->getParsedBody();
        $tagihan = new \Bimbel\Pembayaran\Model\TagihanDetail();

        $tagihan = $this->queryPendapatan($postData);
        $total_pendapatan = array_reduce($tagihan, function($result, $array) {
            $result += $array->total;
            return $result;
        }, 0);

        $pengeluaran = $this->queryPengeluaran($postData);

        $data = [
            'judul' => "Laba Rugi",
            'pendapatans' => $tagihan,
            'total_pendapatan' => $total_pendapatan,
            'pengeluarans' => $pengeluaran,
            'total_pengeluaran' => $pengeluaran->sum('total'),
            'total_laba' => $total_pendapatan - $pengeluaran->sum('total'),
            'periode' => $this->convertDate($postData['start_date']). " ~ " .$this->convertDate($postData['end_date'])
        ];

        $result = $this->toPdf("Report/View/labarugi.twig", $data);
        return $result;
    }

    public function getGaji($request, $args)
    {
        $postData = $request->getParsedBody();
        $gaji = new \Bimbel\Pengeluaran\Model\Gaji();
        $gaji = $gaji
            ->whereDate('tanggal', '>=', $postData['start_date'])
            ->whereDate('tanggal', '<=', $postData['end_date']);

        if (array_key_exists('tempat_kursus', $postData) && !empty($postData['tempat_kursus']))
        {
            $gaji = $gaji->whereHas('guru', function($q) use ($postData) {
                $q->where('kursus_id', $postData['tempat_kursus']);
            });
        }

        $gaji = $gaji->get();

        $data = [
            'judul' => "Gaji Guru",
            'gajis' => $gaji,
            'total_gaji' => $gaji->sum('komisi'),
            'periode' => $this->convertDate($postData['start_date']). " ~ " .$this->convertDate($postData['end_date'])
        ];

        $result = $this->toPdf("Report/View/gaji.twig", $data);
        return $result;
    }

    public function getPendapatan($request, $args)
    {
        $postData = $request->getParsedBody();
        $tagihan = $this->queryPendapatan($postData);
        $total_pendapatan = array_reduce($tagihan, function($result, $value) {
            $result += $value->total;
            return $result;
        }, 0);

        $data = [
            'judul' => "Pendapatan",
            'pendapatans' => $tagihan,
            'total_pendapatan' => $total_pendapatan,
            'periode' => $this->convertDate($postData['start_date']). " ~ " .$this->convertDate($postData['end_date'])
        ];

        $result = $this->toPdf("Report/View/pendapatan.twig", $data);
        return $result;
    }
    
    public function getPengeluaran($request, $args)
    {
        $postData = $request->getParsedBody();
        $pengeluaran = $this->queryPengeluaran($postData);

        $data = [
            'judul' => "Pengeluaran",
            'pengeluarans' => $pengeluaran,
            'total_pengeluaran' => $pengeluaran->sum('total'),
            'periode' => $this->convertDate($postData['start_date']). " ~ " .$this->convertDate($postData['end_date'])
        ];

        $result = $this->toPdf("Report/View/pengeluaran.twig", $data);
        return $result;
    }

    public function getDeposit($request, $args)
    {
        $postData = $request->getParsedBody();
        $deposit = new \Bimbel\Siswa\Model\Deposit();
        $deposit = $deposit
            ->whereDate('tanggal', '>=', $postData['start_date'])
            ->whereDate('tanggal', '<=', $postData['end_date'])
            ->where('status', 'a');

        if (array_key_exists('tempat_kursus', $postData) && !empty($postData['tempat_kursus']))
        {
            $deposit = $deposit->whereHas('siswa', function($q) use ($postData) {
                $q->where('kursus_id', $postData['tempat_kursus']);
            });
        }

        $deposit = $deposit->get();

        $data = [
            'judul' => "Deposit",
            'deposits' => $deposit,
            'total' => $deposit->sum('nominal'),
            'periode' => $this->convertDate($postData['start_date']). " ~ " .$this->convertDate($postData['end_date'])
        ];

        $result = $this->toPdf("Report/View/deposit.twig", $data);
        return $result;
    }
}