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
            $filtered_data = ['siswa', 'deposit', 'tagihan'];
            
            if (in_array($model_name, $filtered_data))
            {
                $session = new \Bimbel\Master\Model\Session();
                $siswa_ids = $session->getSiswaIds();

                if ($siswa_ids !== false)
                {
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
