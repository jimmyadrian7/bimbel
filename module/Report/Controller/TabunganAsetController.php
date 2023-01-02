<?php
namespace Bimbel\Report\Controller;

use \Bimbel\Master\Controller\Controller;
use \Bimbel\Pengeluaran\Model\TabunganAset;

class TabunganAsetController extends Controller
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

        $tabungan_aset = new TabunganAset();

        $tabungan_aset = $tabungan_aset->find($args['tabungan_aset_id']);
        $data['tabungan_aset']  = $tabungan_aset;
        $data['total'] = $tabungan_aset->cicilan_aset->sum('nominal');

        $html = $twig->fetch("Report/View/tabungan_aset.twig", $data);
        
        $pdf->loadHtml($html);
        $pdf->render();
        $result = $pdf->output();
        $result = ["data" => base64_encode($result)];
        $result = json_encode($result);

        return $result;
    }
}