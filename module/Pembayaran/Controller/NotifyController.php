<?php
namespace Bimbel\Pembayaran\Controller;

use \Bimbel\Master\Controller\Controller;
use \Slim\Exception\HttpNotFoundException;

class NotifyController extends Controller
{
    public function notifyInvoice($request, $args)
    {
        try 
        {
            $tagihan_id = $args['tagihan_id'];
            $tagihan = new \Bimbel\Pembayaran\Model\Tagihan();
            $tagihan = $tagihan->find($tagihan_id);
            $siswa = $tagihan->siswa;
            
            if (empty($siswa->orang->no_hp))
            {
                throw new \Error("No Hp siswa kosong");
            }

            $configuration = new \Bimbel\Master\Model\AccountConfiguration();
            $configuration = $configuration->first();

            $wa = new \Bimbel\Whatsapp\Model\Whatsapp();
            $result = $wa->sendMessageTemplate(
                $configuration->wa_invoice_template, 
                $configuration->wa_invoice_template_language, 
                $siswa->orang->no_hp,
                $siswa->orang->nama
            );

            if ($result['status'] != '200' && $result['status'] != '201')
            {
                $errMsg = $result['data'];
                $errMsg = json_decode($errMsg);
                throw new \Error($errMsg->error->message);
            }

            return true;
        }
        catch(\Error $e) 
        {
            throw new \Exception($e->getMessage());
        }
    }

    public function fixData($request, $args)
    {
        $result = ["data" => "success"];

        try
        {
            // Fix data tagihan detail
            $siswa = new \Bimbel\Siswa\Model\Siswa();
            $siswas = $siswa->get();

            foreach($siswas as $siswa)
            {
                foreach($siswa->iuran_terbuat as $iuran_terbuat)
                {
                    if ($iuran_terbuat->tahun == NULL || $iuran_terbuat->bulan == NULL)
                    {
                        continue;
                    }
                    
                    $pembiayaan_ids = $iuran_terbuat->iuran->iuran_detail->pluck('pembiayaan_id')->toArray();

                    $tagihan_details = new \Bimbel\Pembayaran\Model\TagihanDetail();
                    $tagihan_details = $tagihan_details
                        ->whereHas('tagihan', function($q) use ($siswa){
                            $q->where('siswa_id', $siswa->id);
                        })
                        ->whereIn('pembiayaan_id', $pembiayaan_ids)
                        ->get();

                    $tanggal_mulai = $iuran_terbuat->tahun . "-" . $iuran_terbuat->bulan . "-1";
                    $tanggal_berakhir = new \DateTime($tanggal_mulai);
                    $tanggal_berakhir = $tanggal_berakhir->modify("+" . ($iuran_terbuat->iuran->bulan-1) . "month");
                    $tanggal_berakhir = $tanggal_berakhir->format("Y-n-1");
                        
                    foreach($tagihan_details as $tagihan_detail)
                    {
                        $tagihan_detail_value = [
                            "system" => true,
                            "tanggal_iuran_mulai" => $tanggal_mulai,
                            "tanggal_iuran_berakhir" => $tanggal_berakhir,
                            'nominal' => $tagihan_detail->nominal,
                            'qty' => $tagihan_detail->qty,
                            'pembiayaan_id' => $tagihan_detail->pembiayaan_id,
                            'tagihan_id' => $tagihan_detail->tagihan_id,
                            'bulan' => $iuran_terbuat->iuran->bulan
                        ];

                        $tagihan_detail->update($tagihan_detail_value);
                    }
                }
            }

            // fix data tagihan
            $tagihans = new \Bimbel\Pembayaran\Model\Tagihan();
            $tagihans = $tagihans->where('status', 'l')->get();

            foreach ($tagihans as $tagihan)
            {
                $transaksi_terkakhir = $tagihan->transaksi()->where('status', 'v')->latest('tanggal')->first();
                
                $tagihan->update([
                    'tanggal_lunas' => $transaksi_terkakhir->tanggal,
                    'siswa_id' => $tagihan->siswa_id
                ]);
            }
        }
        catch(\Error $e) 
        {
            throw new \Exception($e->getMessage());
        }

        return $result;
    }
}
