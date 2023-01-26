<?php
namespace Bimbel\Guru\Controller;

use \Bimbel\Master\Controller\Controller;
use \Bimbel\Siswa\Model\Siswa;
use \Bimbel\Guru\Model\Guru;
use \Bimbel\Master\Model\Session;
use Illuminate\Database\Capsule\Manager as DB;

class FetchController extends Controller
{
    public function getGuru($request, $args)
    {
        $result = [];

        try 
        {
            $session = new Session();
            $getData = $request->getQueryParams();
            
            $guru = new Guru();

            if (!$session->isSuperUser())
            {
                $guru_ids = $session->getGuruIds();
                $guru = $guru->whereIn('id', $guru_ids);
            }

            $data = $guru->whereHas('orang', function($q) use ($getData) {
                $q->where('nama', 'like', '%' . $getData['query'] . '%');
            })->where('status', 'a')->get();

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

    public function getGuruAvailable($args)
    {
        $session = new Session();
        $result = [];

        try {
            $additionalQuery = "1 = 1";
            $query = "
                SELECT guru.id AS guru_id FROM guru
                LEFT JOIN siswa ON siswa.guru_id = guru.id
                LEFT JOIN jadwal ON jadwal.siswa_id = siswa.id AND jadwal.hari = :hari
                WHERE guru.status = 'a' AND %s
                GROUP BY siswa.guru_id, guru.id
                HAVING COUNT(guru_id) <= :limit
            ";

            if (!$session->isSuperUser())
            {
                $guru_ids = $session->getGuruIds();
                $additionalQuery = "guru.id IN (%s)";
                $additionalQuery = sprintf($additionalQuery, join(",", $guru_ids));
            }
            $query = sprintf($query, $additionalQuery);

            $guru_ids = DB::select(
                DB::raw($query), 
                [
                    'hari' => $args['hari'],
                    'limit' => 7
                ]
            );

            $guru_ids = array_reduce($guru_ids, function($result, $array) {
                $result[] = $array->guru_id;
                return $result;
            }, []);

            $guru = new Guru();
            $guru = $guru->with('orang')->whereIn('id', $guru_ids);

            $result = $guru->get();
        }
        catch (\Error $e)
        {
            throw new \Exception($e->getMessage());
        }

        return $result;
    }
}
