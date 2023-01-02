<?php
namespace Bimbel\Report\Controller;

use \Bimbel\Master\Controller\Controller;
use \Bimbel\Pengeluaran\Model\Gaji;

class GajiController extends Controller
{
    public function getReport($request, $args)
    {
        $postData = $request->getParsedBody();
        $tanggal_between = [$postData['start_date'], $postData['end_date']];
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
        $data['judul'] = "Gaji Guru";
        $data['periode'] = (new \Datetime($postData['start_date']))->format('d/m/Y');
        $data['periode'] = $data['periode']. " ~ " .(new \Datetime($postData['end_date']))->format('d/m/Y');

        $gaji = new Gaji();

        $gaji = $gaji->whereBetween('tanggal', $tanggal_between)->get();

        $data['gajis'] = $gaji;
        $data['total_gaji'] = $gaji->sum('komisi');

        $html = $twig->fetch("Report/View/gaji.twig", $data);
        
        $pdf->loadHtml($html);
        $pdf->render();
        $result = $pdf->output();
        $result = ["data" => base64_encode($result)];
        $result = json_encode($result);

        return $result;
    }
}