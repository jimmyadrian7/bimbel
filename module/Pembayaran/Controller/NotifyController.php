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
}