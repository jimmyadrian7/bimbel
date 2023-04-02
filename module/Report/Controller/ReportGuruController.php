<?php
namespace Bimbel\Report\Controller;

use \Bimbel\Report\Controller\BaseReportController;

class ReportGuruController extends BaseReportController
{
    public function getPendapatan($request, $args, $response)
    {
        $result = [];

        try
        {
            $postData = $request->getParsedBody();
            $data_row = [];

            $gaji = new \Bimbel\Pengeluaran\Model\Gaji();
            $tagihans = new \Bimbel\Pembayaran\Model\Tagihan();
            $tagihan_details = new \Bimbel\Pembayaran\Model\TagihanDetail();

            $tanggal = explode("-", $postData['start_date']);
            $year = $tanggal[0];
            $month = $tanggal[1];
            $total_pendapatan = 0;
            

            // Komisi selain iuran
            $tagihan_details = $gaji->getTagihanDetailDll($postData['guru_id'], $year, $month);
            $tagihan_details = $tagihan_details->map->format();
            $total_pendapatan += $tagihan_details->sum("komisi");
            $data_row = array_merge($data_row, $tagihan_details->toArray());

            // Komisi iuran
            $tagihan_details = $gaji->getTagihanDetailIuran($postData['guru_id'], $year, $month);
            $tagihan_details = $tagihan_details->map->format();
            $total_pendapatan += $tagihan_details->sum("komisi");
            $data_row = array_merge($data_row, $tagihan_details->toArray());

            $tagihan_details = $gaji->getTunjangan($postData['guru_id']);
            $tagihan_details = $tagihan_details->map->format();
            $total_pendapatan += $tagihan_details->sum("komisi");
            $data_row = array_merge($data_row, $tagihan_details->toArray());

            $data = [
                'judul' => "Pendapatan Guru",
                'periode' => $this->convertDate($postData['start_date'], "F Y"),
                "data" => $data_row,
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