<?php
namespace Bimbel\Pengeluaran\Controller;

use \Bimbel\Master\Controller\Controller;
use \Bimbel\Pengeluaran\Model\Gaji;
use \Bimbel\Pengeluaran\Model\Pengeluaran;
use \Bimbel\Guru\Model\Guru;
use Illuminate\Database\Capsule\Manager as DB;

class GajiController extends Controller
{
    public function generateGaji($request, $args)
    {
        $result = false;

        try 
        {
            DB::beginTransaction();
            
            $data = $request->getParsedBody();
            if (count($data) === 0)
            {
                throw new \Error("Data is empty");
            }
            if (!array_key_exists('date', $data))
            {
                throw new \Error("Date is empty");
            }

            $total_gaji = 0;
            $date = explode("-", $data['date']);
            $year = $date[0];
            $month = $date[1];

            $gaji = new Gaji();
            $gaji = $gaji->whereYear("tanggal", $year)->whereMonth("tanggal", $month)->get();
            $guru_ids = $gaji->pluck('guru_id');

            $guru = new Guru();
            $gurus = $guru->where('status', 'a')->whereNotIn('id', $guru_ids)->get();

            // $pengeluaran = new Pengeluaran();
            // $pengeluaran = $pengeluaran->create([
            //     "nama" => sprintf("Gaji Guru (%s)", date('m')),
            //     "jumlah" => 1,
            //     "harga" => $total_gaji,
            //     "gaji" => true
            // ]);
            
            foreach ($gurus as $guru)
            {
                $gaji = new Gaji();
                $gaji = $gaji->create([
                    'guru_id' => $guru->id,
                    // 'pengeluaran_id' => $pengeluaran->id,
                    'tanggal' => $data['date']
                ]);
                $total_gaji += $gaji->total;
            }

            // $pengeluaran->update(['harga' => $total_gaji, 'jumlah' => $pengeluaran->jumlah]);
            
            $result = true;

            DB::commit();
        }
        catch(\Error $e) 
        {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }

        return $result;
    }
}
