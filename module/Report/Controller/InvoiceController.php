<?php
namespace Bimbel\Report\Controller;

use \Bimbel\Report\Controller\BaseReportController;

class InvoiceController extends BaseReportController
{
    public function getTagihan($request, $args)
    {
        $tagihan = new \Bimbel\Pembayaran\Model\Tagihan();
        $tagihan = $tagihan->find($args['tagihan_id']);
        $data = [
            'title' => true,
            'judul' => 'Invoice',
            'tagihan' => $tagihan,
            'nomor' => $tagihan->code
        ];

        $result = $this->toPdf("Report/View/tagihan.twig", $data);
        return $result;
    }

    public function getTabunganAset($request, $args)
    {
        $tabungan_aset = new \Bimbel\Pengeluaran\Model\TabunganAset();
        $tabungan_aset = $tabungan_aset->find($args['tabungan_aset_id']);
        $data = [
            'title' => true,
            'judul' => 'Invoice',
            'nomor' => $tabungan_aset->code,
            'tabungan_aset' => $tabungan_aset,
            'total' => $tabungan_aset->cicilan_aset->sum('nominal')
        ];

        $result = $this->toPdf("Report/View/tabungan_aset.twig", $data);
        return $result;
    }
}