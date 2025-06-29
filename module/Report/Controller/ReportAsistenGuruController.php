<?php
namespace Bimbel\Report\Controller;

use \Bimbel\Report\Controller\BaseReportController;
use Illuminate\Support\Collection;
use \Bimbel\Guru\Model\PotonganGaji;
use Illuminate\Database\Capsule\Manager as DB;

class ReportAsistenGuruController extends BaseReportController
{
    public function getSlipGaji($request, $args, $response)
    {
        $result = [];

        try
        {
            $postData = $request->getParsedBody();
            $data_row = collect([]);

            $asisten_guru = new \Bimbel\Guru\Model\AsistenGuru();
            $potongan_gaji = new PotonganGaji();
            
            $asisten_guru = $asisten_guru->find($postData['asisten_guru_id']);
            $total_gaji = 0;

            $tanggal_gaji = \DateTime::createFromFormat("Y-m-d", $postData['start_date']);
            $tahun_gaji = $tanggal_gaji->format('Y');
            $bulan_gaji = $tanggal_gaji->format('m');
            
            // Potongan Gaji
            $potongan_gaji = $potongan_gaji
                // ->select(DB::raw('SUM(nominal) AS nominal'))
                ->where('asisten_guru_id', $postData['asisten_guru_id'])
                ->whereYear('tanggal', $tahun_gaji)
                ->whereMonth('tanggal', $bulan_gaji)
                ->get();
            $total_potongan = $potongan_gaji->sum('nominal');
            
            $tunjangan_guru = $asisten_guru->tunjangan_guru;
            $cabang = $asisten_guru->kursus->first();

            $total_tunjangan = $tunjangan_guru->sum(function ($tunjangan_guru) {
                return $tunjangan_guru['nominal'] * $tunjangan_guru['jumlah'];
            });

            $total_diterima = $total_tunjangan + $total_potongan;
            $smile_images = $this->getImage('Cus-2.jpg');
            
            $data = [
                'judul' => "SLIP GAJI " . strtoupper($asisten_guru->jabatan),
                'periode' => $this->convertDate($postData['start_date'], "F Y"),
                'tanggal' => $this->convertDate($postData['start_date'], "d F Y"),
                'guru' => $asisten_guru,
                'cabang' => $cabang->nama,
                'gaji_pokok' => $total_potongan,
                'tunjangan_guru' => $tunjangan_guru,
                'kursus' => $cabang,
                'total_tunjangan' => $total_tunjangan,
                'potongan_gaji' => [],
                'total_diterima' => $total_diterima,
                'smile_images' => $smile_images,
            ];

            $result = $this->toPdf("Report/View/slip_gaji.twig", $data, "background slip gaji.jpg", 'potrait');
        }
        catch(\Error $e)
        {
            $result = $this->container->get('error')($e, $response);
            $result = json_encode($result);
        }

        return $result;
    }
}