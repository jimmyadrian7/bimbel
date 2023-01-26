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

            $guru = new Guru();
            $gurus = $guru->where('status', 'a')->get();
            
            foreach ($gurus as $guru)
            {
                $gaji = new Gaji();
                $gaji = $gaji
                    ->whereYear("tanggal", $year)
                    ->whereMonth("tanggal", $month)
                    ->where('guru_id', $guru->id)
                    ->first();

                $gaji_value = [
                    'guru_id' => $guru->id,
                    'tanggal' => $data['date']
                ];

                if ($gaji)
                {
                    $gaji->update($gaji_value);
                }
                else
                {
                    $gaji = $gaji->create($gaji_value);
                }

                
                $total_gaji += $gaji->total;
            }
            
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
