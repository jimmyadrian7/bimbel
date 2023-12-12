<?php
namespace Bimbel\Report\Controller;

use \Bimbel\Report\Controller\BaseReportController;
use Illuminate\Support\Collection;

class ReportGuruController extends BaseReportController
{
    public function getPendapatan($request, $args, $response)
    {
        $result = [];

        try
        {
            $postData = $request->getParsedBody();
            $data_row = collect([]);

            $guru = new \Bimbel\Guru\Model\Guru();
            $gaji = new \Bimbel\Pengeluaran\Model\Gaji();
            $tagihans = new \Bimbel\Pembayaran\Model\Tagihan();
            $tagihan_details = new \Bimbel\Pembayaran\Model\TagihanDetail();
            $transaksi = new \Bimbel\Pembayaran\Model\Transaksi();

            $jenis_pembayaran_list = [];
            foreach ($transaksi->jenis_pembayaran_enum as $key => $value) {
                $jenis_pembayaran_list[$value['value']] = $value['label'];
            }

            $tanggal = explode("-", $postData['start_date']);
            $year = $tanggal[0];
            $month = $tanggal[1];
            $total_pendapatan = 0;
            
            $guru = $guru->find($postData['guru_id']);

            // Komisi selain iuran
            $tagihan_details = $gaji->getTagihanDetailDll($postData['guru_id'], $year, $month);
            $tagihan_details = $tagihan_details->map->format();
            $data_row = $data_row->merge($tagihan_details);

            // Komisi iuran
            $tagihan_details = $gaji->getTagihanDetailIuran($postData['guru_id'], $year, $month);
            $tagihan_details = $tagihan_details->map->format();
            $data_row = $data_row->merge($tagihan_details);

            $tagihan_details = $gaji->getTunjangan($postData['guru_id']);
            $tagihan_details = $tagihan_details->map->format();
            $data_row = $data_row->merge($tagihan_details);
            
            // Sort by Komisi
            $data_row = $data_row->sortBy('persen_komisi');
            $total_pendapatan = $data_row->sum('komisi');

            $data = $data_row->groupBy(['kursus', 'jenis_transaksi']);
            // $data_row = $data_row->toArray();
            
            $data = [
                'judul' => "Pendapatan " . $guru->orang->nama,
                'periode' => $this->convertDate($postData['start_date'], "F Y"),
                'jenis_pembayaran_list' => $jenis_pembayaran_list,
                "data" => $data,
                "total_pendapatan" => $total_pendapatan
            ];

            $result = $this->toPdf("Report/View/pendapatan_guru.twig", $data);
        }
        catch(\Error $e)
        {
            $result = $this->container->get('error')($e, $response);
            $result = json_encode($result);
        }

        return $result;
    }
}