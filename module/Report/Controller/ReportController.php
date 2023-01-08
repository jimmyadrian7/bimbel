<?php
namespace Bimbel\Report\Controller;

use \Bimbel\Report\Controller\BaseReportController;
use Illuminate\Database\Capsule\Manager as DB;

class ReportController extends BaseReportController
{
    public function getLabaRugi($request, $args)
    {
        $postData = $request->getParsedBody();
        $tagihan = new \Bimbel\Pembayaran\Model\TagihanDetail();

        $query = "
            SELECT 
                tagihan_detail.nama AS nama,
                SUM(tagihan_detail.qty) AS qty, 
                SUM(tagihan_detail.total - tagihan_detail.komisi) AS total
            FROM tagihan_detail
            LEFT JOIN tagihan ON tagihan.id = tagihan_detail.tagihan_id
            WHERE 
                DATE(tagihan.tanggal) >= :start_date AND
                DATE(tagihan.tanggal) <= :end_date
            GROUP BY tagihan_detail.nama
        ";
        $tagihan = DB::select(
            DB::raw($query), 
            [
                'start_date' => $postData['start_date'],
                'end_date' => $postData['end_date']
            ]
        );
        $total_pendapatan = array_reduce($tagihan, function($result, $array) {
            $result += $array->total;
            return $result;
        }, 0);

        $pengeluaran = new \Bimbel\Pengeluaran\Model\Pengeluaran();
        $pengeluaran = $pengeluaran
            ->whereDate('tanggal', '>=', $postData['start_date'])
            ->whereDate('tanggal', '<=', $postData['end_date'])
            ->get();

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
            ->whereDate('tanggal', '<=', $postData['end_date'])
            ->get();

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
            LEFT JOIN siswa ON siswa.id = tagihan.siswa_id
            WHERE 
                DATE(tagihan.tanggal) >= :start_date AND 
                DATE(tagihan.tanggal) <= :end_date AND
                %s
            GROUP BY tagihan_detail.nama
        ";
        $where_condition = "1=1";

        if (array_key_exists('tempat_kursus', $postData) && !empty($postData['tempat_kursus']))
        {
            $where_condition = "siswa.kursus_id = :tempat_kursus";
            $params['tempat_kursus'] = $postData['tempat_kursus'];
        }

        $query = sprintf($query, $where_condition);
        $tagihan = DB::select(DB::raw($query), $params);

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
        $pengeluaran = new \Bimbel\Pengeluaran\Model\Pengeluaran();
        $pengeluaran = $pengeluaran
            ->whereDate('tanggal', '>=', $postData['start_date'])
            ->whereDate('tanggal', '<=', $postData['end_date'])
            ->get();

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
            ->where('status', 'a')
            ->get();

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