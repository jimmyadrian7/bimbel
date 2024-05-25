<?php
namespace Bimbel\Report\Controller;

use \Bimbel\Report\Controller\BaseReportController;

class InvoiceController extends BaseReportController
{
    public function getTagihan($request, $args, &$response)
    {
        $result = [];

        try
        {
            $tagihan = new \Bimbel\Pembayaran\Model\Tagihan();
            $tagihan = $tagihan->find($args['tagihan_id']);

            $data = [
                'title' => true,
                'judul' => 'Invoice',
                'tagihan' => $tagihan,
                'siswa' => $tagihan->siswa->orang->nama,
                'nomor' => $tagihan->code
            ];

            $result = $this->toPdf("Report/View/tagihan.twig", $data);
        }
        catch(\Error $e)
        {
            $result = $this->container->get('error')($e, $response);
        }
        
        return $result;
    }

    public function getTabunganAset($request, $args, &$response)
    {
        $result = [];

        try
        {
            $tabungan_aset = new \Bimbel\Pengeluaran\Model\TabunganAset();
            $tabungan_aset = $tabungan_aset->find($args['tabungan_aset_id']);

            $cicilan_asets = $tabungan_aset->cicilan_aset()->where('status', 's')->get();
            $penarikans = $tabungan_aset->penarikan()->where('status', 's')->get();

            $data = [
                'title' => true,
                'judul' => 'Invoice',
                'nomor' => $tabungan_aset->code,
                'tabungan_aset' => $tabungan_aset,
                'cicilan_asets' => $cicilan_asets,
                'penarikans' => $penarikans,
                'total_cicilan' => $cicilan_asets->sum('nominal'),
                'total_penarikan' => $penarikans->sum('nominal')
            ];

            $result = $this->toPdf("Report/View/invoice.twig", $data);
        }
        catch(\Error $e)
        {
            $result = $this->container->get('error')($e, $response);
        }
        
        return $result;
    }
}