<?php
namespace Bimbel\Master\Controller;

use \Bimbel\Master\Controller\Controller;
use \Slim\Exception\HttpNotFoundException;

class FetchController extends Controller
{
    public function fetchDatas($request, $args)
    {
        try 
        {
            $model_name = $args["models"];
            if($model_name[strlen($model_name)-1] != "s")
            {
                throw new HttpNotFoundException($request);
            }
            $model_name = substr($model_name, 0, (strlen($model_name) - 1));
            $model = $this->getModel($model_name);
            $settings = $this->container->get("settings");
            
            // $data = $model->paginate($settings["settings"]["row_per_page"]);

            $session = $this->container->get('Session');
            $user = $session->get('user');
            $isGuru = count($user->role->where('kode', 'G')) === 1 ? true : false;
            $isSiswa = count($user->role->where('kode', 'S')) === 1 ? true : false;

            if (($isGuru || $isSiswa) && !$user->super_user)
            {
                $filtered_data = ['siswa', 'deposit', 'tagihan'];
                if (in_array($model_name, $filtered_data))
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

                    if ($model_name == 'siswa')
                    {
                        $model = $model->whereIn('id', $siswa_ids);
                    }
                    else
                    {
                        $model = $model->whereIn('siswa_id', $siswa_ids);
                    }
                }
            }

            $data = [
                "data" => $model->get()
            ];

            return $data;
        }
        catch(\Error $e) 
        {
            throw new \Exception($e->getMessage());
        }
    }

    public function fetchData($request, $args)
    {
        try 
        {
            $model_id = $args["model_id"];
            $model = $this->getModel($args["model"]);
            $data = $model->find($model_id);
            return $data;

        }
        catch(\Error $e) 
        {
            throw new \Exception($e->getMessage());
        }
    }
}
