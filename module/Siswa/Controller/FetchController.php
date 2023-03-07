<?php
namespace Bimbel\Siswa\Controller;

use \Bimbel\Master\Controller\Controller;
use \Bimbel\Siswa\Model\Siswa;

class FetchController extends Controller
{
    public function getSiswa($request, $args, &$response)
    {
        $result = [];

        try 
        {
            $getData = $request->getQueryParams();
            
            $siswa = new Siswa();

            if (array_key_exists("query", $getData))
            {
                $data = $siswa->whereHas('orang', function($q) use ($getData) {
                    $q->where('nama', 'like', '%' . $getData['query'] . '%');
                })->where('status', 'a');
            }
            else
            {
                $data = $siswa->where('status', 'a');
            }

            $session = new \Bimbel\Master\Model\Session();
            $siswa_ids = $session->getSiswaIds();

            if ($siswa_ids !== false)
            {
                $data = $data->whereIn('id', $siswa_ids);
            }

            $data = $data->get();
            foreach ($data as &$value) {
                $value->{"text"} = $value->orang->nama;
            }

            $data = $data->map->only(["id", "text"]);
            
            return $data;
        }
        catch(\Error $e)
        {
            $result = $this->container->get('error')($e, $response);
        }

        return $result;
    }

    public function generateTagihan($request, $args, &$response)
    {
        $result = true;

        try 
        {
            $data = $request->getParsedBody();            
            $siswa = new Siswa();

            $siswa = $siswa->find($data['id']);

            if ($data['reset'])            
            {
                $siswa->iuran_terbuat()->delete();
                
                foreach($siswa->tagihan as $tagihan)
                {
                    $tagihan->delete();
                }

                $siswa->recreateIuran();
            }

            $siswa->triggerIuran($data['reset'], $data['tanggal']);
        }
        catch(\Error $e)
        {
            $result = $this->container->get('error')($e, $response);
        }

        return $result;
    }
}
