<?php
namespace Bimbel\Report\Controller;

use \Bimbel\Master\Controller\Controller;
use \Bimbel\Pembayaran\Model\TagihanDetail;
use \Bimbel\Pengeluaran\Model\Pengeluaran;

class LabaRugiController extends Controller
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
        $data['judul'] = "Laba Rugi";
        $data['periode'] = (new \Datetime($postData['start_date']))->format('d/m/Y');
        $data['periode'] = $data['periode']. " ~ " .(new \Datetime($postData['end_date']))->format('d/m/Y');

        $tagihan = new TagihanDetail();
        $pengeluaran = new Pengeluaran();

        $tagihan = $tagihan->whereHas('tagihan', function($q) use ($tanggal_between) {
            $q->whereBetween('tanggal', $tanggal_between);
        })->get();

        if ($tagihan->count() > 0)
        {
            $tagihan = $tagihan->groupBy('nama');
            $tagihan = $tagihan->map(function($group) {
                return [
                    'deskripsi' => $group->first()['nama'],
                    'jumlah' => $group->sum('qty'),
                    'total' => $group->sum('total') - $group->first()['komisi']
                ];
            });
        }
        $data['pendapatans']  = $tagihan;
        $data['total_pendapatan']  = $tagihan->sum('total');

        $pengeluaran = $pengeluaran->whereBetween('tanggal', $tanggal_between)->get();
        $data['pengeluarans']  = $pengeluaran;
        $data['total_pengeluaran'] = $pengeluaran->sum('total');

        $data['total_laba']  = $data['total_pendapatan'] - $data['total_pengeluaran'];

        $html = $twig->fetch("Report/View/labarugi.twig", $data);
        
        $pdf->loadHtml($html);
        $pdf->render();
        $result = $pdf->output();
        $result = ["data" => base64_encode($result)];
        $result = json_encode($result);

        return $result;
    }
}