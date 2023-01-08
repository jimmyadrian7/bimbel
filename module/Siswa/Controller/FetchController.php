<?php
namespace Bimbel\Siswa\Controller;

use \Bimbel\Master\Controller\Controller;
use \Bimbel\Siswa\Model\Siswa;

class FetchController extends Controller
{
    public function getSiswa($request, $args)
    {
        $result = [];

        try 
        {
            $getData = $request->getQueryParams();
            
            $siswa = new Siswa();
            $data = $siswa->without(['tagihan'])->whereHas('orang', function($q) use ($getData) {
                $q->where('nama', 'like', '%' . $getData['query'] . '%');
            });

            $session = new \Bimbel\Master\Model\Session();
            $siswa_ids = $session->getSiswaIds();

            if ($siswa_ids !== false)
            {
                $data = $data->whereIn('id', $siswa_ids);
            }

            $data = $data->get();
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
}
