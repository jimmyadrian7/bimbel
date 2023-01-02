<?php
namespace Bimbel\Report\Controller;

use \Bimbel\Master\Controller\Controller;
use \Bimbel\Pengeluaran\Model\Pengeluaran;

class PengeluaranController extends Controller
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
        $data['judul'] = "Pengeluaran";
        $data['periode'] = (new \Datetime($postData['start_date']))->format('d/m/Y');
        $data['periode'] = $data['periode']. " ~ " .(new \Datetime($postData['end_date']))->format('d/m/Y');

        $pengeluaran = new Pengeluaran();

        $pengeluaran = $pengeluaran->whereBetween('tanggal', $tanggal_between)->get();
        $data['pengeluarans']  = $pengeluaran;
        $data['total_pengeluaran'] = $pengeluaran->sum('total');

        $html = $twig->fetch("Report/View/pengeluaran.twig", $data);
        
        $pdf->loadHtml($html);
        $pdf->render();
        $result = $pdf->output();
        $result = ["data" => base64_encode($result)];
        $result = json_encode($result);

        return $result;
    }
}