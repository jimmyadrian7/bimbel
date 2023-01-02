<?php
namespace Bimbel\Guru\Controller;

use \Bimbel\Master\Controller\Controller;
use \Bimbel\Siswa\Model\Siswa;
use \Bimbel\Guru\Model\Guru;

class FetchController extends Controller
{
    public function getGuru($request, $args)
    {
        $result = [];

        try 
        {
            $getData = $request->getQueryParams();
            
            $guru = new Guru();
            $data = $guru->without(['siswa', 'gaji'])->whereHas('orang', function($q) use ($getData) {
                $q->where('nama', 'like', '%' . $getData['query'] . '%');
            })->get();

            foreach ($data as &$value) {
                $value["nama"] = $value["orang"]["nama"];
                $value->{"nama"} = $value->orang->nama;
            }

            $data = $data->map->only(["id", "nama"]);
            
            return $data;
        }
        catch(\Error $e) 
        {
            throw new \Exception($e->getMessage());
        }

        return $result;
    }

    public function getSiswaGuru($request, $args)
    {
        $result = [];

        try
        {
            $siswa = new Siswa();
            $siswa = $siswa->where('guru_id', $args['guru_id'])->get();
            $result = $siswa;
        }
        catch (\Error $e)
        {
            throw new \Exception($e->getMessage());
        }

        return $result;
    }
}
