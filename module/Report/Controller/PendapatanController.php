<?php
namespace Bimbel\Report\Controller;

use \Bimbel\Master\Controller\Controller;
use Illuminate\Database\Capsule\Manager as DB;

class PendapatanController extends Controller
{
    public function getReport($request, $args)
    {
        $postData = $request->getParsedBody();
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
        $data['judul'] = "Pendapatan";
        $data['periode'] = (new \Datetime($postData['start_date']))->format('d/m/Y');
        $data['periode'] = $data['periode']. " ~ " .(new \Datetime($postData['end_date']))->format('d/m/Y');

        $params = [
            'start_date' => $postData['start_date'],
            'end_date' => $postData['end_date']
        ];

        $query = "
            SELECT 
                nama AS deskripsi, SUM(qty) AS qty, 
                sum(tagihan_detail.total - tagihan_detail.komisi) AS total
            FROM tagihan_detail
            LEFT JOIN tagihan ON tagihan.id = tagihan_detail.tagihan_id
            LEFT JOIN siswa ON siswa.id = tagihan.siswa_id
            WHERE 
                tagihan.tanggal >= :start_date AND 
                tagihan.tanggal <= :end_date AND
                %s
            GROUP BY tagihan_detail.nama
        ";
        $where_condition = "1=1";

        if (array_key_exists('tempat_kursus', $postData) && !empty($postData['tempat_kursus']))
        {
            $where_condition = "siswa.kursus_id = :tempat_kursus";
            $params['tempat_kursus'] = $postData['tempat_kursus'];
        }

        $query = sprintf($query, $where_condition);
        $tagihan = DB::select(DB::raw($query), $params);

        $data['pendapatans']  = $tagihan;
        $data['total_pendapatan']  = array_reduce($tagihan, function($result, $value) {
            $result += $value->total;
            return $result;
        }, 0);

        $html = $twig->fetch("Report/View/pendapatan.twig", $data);
        
        $pdf->loadHtml($html);
        $pdf->render();
        $result = $pdf->output();
        $result = ["data" => base64_encode($result)];
        $result = json_encode($result);

        return $result;
    }
}