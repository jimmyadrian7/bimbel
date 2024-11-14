<?php
namespace Bimbel\FixData\Controller;

use \Bimbel\Master\Controller\Controller;
use \Slim\Exception\HttpNotFoundException;
use Illuminate\Database\Capsule\Manager as DB;
use \Bimbel\FixData\Model\Utils;

class FixDataIuranController extends Controller
{
    public function fixDataIuran($request, $args, &$response)
    {
        $result = ["data" => "success"];

        $wrongData = [];
        $iuran_terbuat = new \Bimbel\Siswa\Model\IuranTerbuat();

        $iuran_terbuat = $iuran_terbuat
        ->whereHas('iuran', function($query) {
            $query->whereHas('iuran_detail', function($q) {
                $q->where('skip', 0);
            });
        })
        ->get();

        foreach ($iuran_terbuat as $key => $it) {
            $iuran_detail = $it->iuran->iuran_detail()->where('skip', 0)->get();
            
            foreach ($iuran_detail as $key => $id) {

                $tagihan_detail = new \Bimbel\Pembayaran\Model\TagihanDetail();
                $tagihan_detail = $tagihan_detail
                    ->where('pembiayaan_id', '=', $id->pembiayaan_id)
                    ->whereHas('tagihan', function($query) use ($it) {
                        $query->where('siswa_id', '=', $it->siswa_id);
                    })
                    ->orderBy('tanggal_iuran_berakhir', 'DESC')
                    ->first();

                if ($tagihan_detail)
                {
                    $latestDate = new \DateTime($tagihan_detail->tanggal_iuran_berakhir);
                    $latestDate = $latestDate->format('Y-n');
                    $latestDate = explode('-', $latestDate);
                    
                    $bulan = $latestDate[1];
                    $tahun = $latestDate[0];

                    $cekbulan = $bulan - $it->bulan;
                    $cektahun = $tahun - $it->tahun;

                    if ($cekbulan != 0 || $cektahun != 0)
                    {
                        $it->update([
                            'bulan' => $latestDate[1],
                            'tahun' => $latestDate[0]
                        ]);
                    }
                }
                
            }            
        }
        
        return $result;
    }
}
