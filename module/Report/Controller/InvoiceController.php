<?php
namespace Bimbel\Report\Controller;

use \Bimbel\Master\Controller\Controller;
use \Bimbel\Pembayaran\Model\Tagihan;

class InvoiceController extends Controller
{
    public function getReport($request, $args)
    {
        $pdf = $this->container->get("pdf");
        $twig = $this->container->get("twig");

        $data = [];
        
        $path = dirname(__DIR__) . "/View/images/";

        $logo = $path . "logo.png";
        $logo = file_get_contents($logo);
        $logo = 'data:image/png;base64, ' . base64_encode($logo);

        $background = $path . "coba.jpg";
        $background = file_get_contents($background);
        $background = 'data:image/png;base64, ' . base64_encode($background);

        $data['logo'] = $logo;
        $data['background'] = $background;
        $data['judul'] = "Invoice";

        $tagihan = new Tagihan();

        $tagihan = $tagihan->find($args['tagihan_id']);
        $data['tagihan']  = $tagihan;

        $html = $twig->fetch("Report/View/invoice.twig", $data);
        
        $pdf->loadHtml($html);
        $pdf->render();
        $result = $pdf->output();
        $result = ["data" => base64_encode($result)];
        $result = json_encode($result);

        return $result;
    }
}