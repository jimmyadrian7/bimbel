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

            $session = $this->container->get('Session');
            $user = $session->get('user');
            $isGuru = count($user->role->where('kode', 'G')) === 1 ? true : false;
            $isSiswa = count($user->role->where('kode', 'S')) === 1 ? true : false;

            if (($isGuru || $isSiswa) && !$user->super_user)
            {
                $siswa_ids = [];
                
                if ($isGuru)
                {
                    $guru = $this->getModel('Guru');
                    $guru = $guru->where('orang_id', $user->orang_id)->first();
                    $siswa_ids = $guru->siswa->pluck('id');
                }

                if ($isSiswa)
                {
                    $siswa = $this->getModel('Siswa');
                    $siswa = $siswa->where('orang_id', $user->orang_id)->first();
                    $siswa_ids = [$siswa->id];
                }

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
