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
            $getData = $request->getQueryParams();
            $model_name = substr($model_name, 0, (strlen($model_name) - 1));
            $model = $this->getModel($model_name);
            $filtered_data = ['siswa', 'deposit', 'tagihan', 'kursus', 'guru', 'aset', 'tabungan_aset', 'pengeluaran'];
            $condition = [];
            $pagination = false;
            $page = 1;

            if (array_key_exists('pagination', $getData))
            {
                $pagination = $getData['pagination'] == "1" ? true : false;
                $page = $getData['page'];
            }

            if (array_key_exists('search', $getData))
            {
                $condition[] = [$model->searchField, 'like', '%'. $getData['search'] .'%'];
            }
            
            if (in_array($model_name, $filtered_data))
            {
                $session = new \Bimbel\Master\Model\Session();

                if (!$session->isSuperUser())
                {
                    switch($model_name)
                    {
                        case 'siswa':
                            $siswa_ids = $session->getSiswaIds();
                            $condition[] = ['id', 'in', $siswa_ids];
                        break;
                        case 'kursus':
                            $kursus_ids = $session->getKursusIds();
                            $condition[] = ['id', 'in', $kursus_ids];
                        break;
                        case 'guru':
                            $guru_ids = $session->getGuruIds();
                            $condition[] = ['id', 'in', $guru_ids];
                        break;
                        case 'aset':
                        case 'tabungan_aset':
                        case 'pengeluaran':
                            $kursus_ids = $session->getKursusIds();
                            $condition[] = ['kursus_id', 'in', $kursus_ids];
                        break;
                        default:
                        $siswa_ids = $session->getSiswaIds();
                        $condition[] = ['siswa_id', 'in', $siswa_ids];
                        break;
                    }
                }
            }

            if ($pagination)
            {
                $data = $model->fetchAllData($condition, $model, $pagination, $page);
            }
            else
            {
                $data = [
                    "data" => $model->fetchAllData($condition, $model)
                ];
            }
            

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
            $data = $model->fetchDetail($model_id, $model);
            return $data;

        }
        catch(\Error $e) 
        {
            throw new \Exception($e->getMessage());
        }
    }

    public function fetchCustomDatas($request, $args)
    {
        try 
        {
            $model_name = $args["models"];
            if($model_name[strlen($model_name)-1] != "s")
            {
                throw new HttpNotFoundException($request);
            }
            $getData = $request->getQueryParams();
            $model_name = substr($model_name, 0, (strlen($model_name) - 1));
            $model = $this->getModel($model_name);
            $filtered_data = ['siswa', 'deposit', 'tagihan', 'kursus', 'guru', 'aset', 'tabungan_aset', 'pengeluaran'];
            $condition = [];
            $sort = [];
            $pagination = false;
            $page = 1;

            if (array_key_exists('pagination', $getData))
            {
                $pagination = $getData['pagination'] == "1" ? true : false;
                $page = $getData['page'];
            }

            if (array_key_exists('search', $getData))
            {
                $condition[] = [$model->searchField, 'like', '%'. $getData['search'] .'%'];
            }
            
            if (in_array($model_name, $filtered_data))
            {
                $session = new \Bimbel\Master\Model\Session();

                if (!$session->isSuperUser())
                {
                    switch($model_name)
                    {
                        case 'siswa':
                            $siswa_ids = $session->getSiswaIds();
                            $condition[] = ['id', 'in', $siswa_ids];
                        break;
                        case 'kursus':
                            $kursus_ids = $session->getKursusIds();
                            $condition[] = ['id', 'in', $kursus_ids];
                        break;
                        case 'guru':
                            $guru_ids = $session->getGuruIds();
                            $condition[] = ['id', 'in', $guru_ids];
                        break;
                        case 'aset':
                        case 'tabungan_aset':
                        case 'pengeluaran':
                            $kursus_ids = $session->getKursusIds();
                            $condition[] = ['kursus_id', 'in', $kursus_ids];
                        break;
                        default:
                        $siswa_ids = $session->getSiswaIds();
                        $condition[] = ['siswa_id', 'in', $siswa_ids];
                        break;
                    }
                }
            }


            $additional = $request->getParsedBody();

            if (array_key_exists('filter', $additional))
            {
                foreach($additional['filter'] as $filter)
                {
                    $valueFilter = $filter['value'];

                    if ($filter['operation'] == 'like')
                    {
                        $valueFilter = "%" . $valueFilter . "%";
                    }

                    $condition[] = [$filter['field'], $filter['operation'], $valueFilter];
                }
            }

            if (array_key_exists('sort', $additional))
            {
                foreach($additional['sort'] as $s)
                {
                    $sort[] = [$s['field'], $s['type']];
                }
            }


            if ($pagination)
            {
                $data = $model->fetchAllData($condition, $model, $pagination, $page, $sort);
            }
            else
            {
                $data = [
                    "data" => $model->fetchAllData($condition, $model)
                ];
            }
            

            return $data;
        }
        catch(\Error $e) 
        {
            throw new \Exception($e->getMessage());
        }
    }
}
