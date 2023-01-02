<?php
namespace Bimbel\Report\Controller;

use \Bimbel\Master\Controller\Controller;
use \Bimbel\Siswa\Model\Deposit;

class DepositController extends Controller
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
        $data['judul'] = "Deposit";
        $data['periode'] = (new \Datetime($postData['start_date']))->format('d/m/Y');
        $data['periode'] = $data['periode']. " ~ " .(new \Datetime($postData['end_date']))->format('d/m/Y');

        $deposit = new Deposit();

        $deposit = $deposit->whereBetween('tanggal', $tanggal_between)->where('status', 'a')->get();
        $data['deposits']  = $deposit;
        $data['total']  = $deposit->sum('nominal');

        $html = $twig->fetch("Report/View/deposit.twig", $data);
        
        $pdf->loadHtml($html);
        $pdf->render();
        $result = $pdf->output();
        $result = ["data" => base64_encode($result)];
        $result = json_encode($result);

        return $result;
    }
}