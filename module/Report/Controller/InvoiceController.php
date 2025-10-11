<?php
namespace Bimbel\Report\Controller;

use \Bimbel\Report\Controller\BaseReportController;
use Ngekoding\Terbilang\Terbilang;

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

    public function getKwitansi($request, $args, &$response)
    {
        $result = [];

        try
        {
            $tagihan = new \Bimbel\Pembayaran\Model\Tagihan();
            $tagihan = $tagihan->find($args['tagihan_id']);

            $tmpt_kursus = $tagihan->kursus;
            if ($tmpt_kursus->logo_bank)
            {
                $logo_bank = 'data:' . $tmpt_kursus->logo_bank->filetype . ';base64, ' . $tmpt_kursus->logo_bank->base64;
            }
            else
            {
                $logo_bank = "";
            }
            
            $terbilang = Terbilang::convert($tagihan->total) . " rupiah";
            $untuk = [];
            $untuk_spp = [];

            // foreach ($tagihan->tagihan_detail as $key => $tagihan_detail) {
            //     if ($tagihan_detail->kategori_pembiayaan == 's')
            //     {
            //         if ($tagihan_detail->tanggal_iuran_mulai == $tagihan_detail->tanggal_iuran_berakhir)
            //         {
            //             $tanggal_iuran = date('F Y', strtotime($tagihan_detail->tanggal_iuran_mulai));
            //         }
            //         else
            //         {
            //             $tanggal_iuran = date('F Y', strtotime($tagihan_detail->tanggal_iuran_mulai)) . " - " . date('F Y', strtotime($tagihan_detail->tanggal_iuran_berakhir));
            //         }
            //         array_push($untuk_spp, "Iuran " . $tanggal_iuran);
            //     }
            //     else
            //     {
            //         array_push($untuk, $tagihan_detail->nama);
            //     }
            // }

            $untuk = implode(", ", $untuk);
            $untuk_spp = implode(", ", $untuk_spp);

            $data = [
                'tagihan' => $tagihan,
                'kursus' => $tmpt_kursus,
                'siswa' => $tagihan->siswa->orang->nama,
                'nomor' => $tagihan->code,
                'smileimage' => $this->getImage('smile-icon.png'),
                'logo_bank' => $logo_bank,
                'untuk' => $untuk,
                'untuk_spp' => $untuk_spp,
                'terbilang' => $terbilang,
            ];

            $result = $this->toPdf("Report/View/kwitansi/kwitansi.twig", $data);
        }
        catch(\Error $e)
        {
            $result = $this->container->get('error')($e, $response);
        }
        
        return $result;
    }
}