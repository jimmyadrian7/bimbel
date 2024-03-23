<?php
namespace Bimbel\Siswa\Controller;

use \Bimbel\Master\Controller\Controller;
use \Bimbel\Siswa\Model\Siswa;
use \Bimbel\Master\Model\Kursus;
use Illuminate\Database\Capsule\Manager as DB;
use \Bimbel\Pembayaran\Model\TagihanDetail;

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

    public function getKursus($request, $args, &$response)
    {
        $result = [];

        try 
        {
            $getData = $request->getQueryParams();
            
            $kursus = new Kursus();
            $data = $kursus->whereRaw('1 = 1');

            if (array_key_exists("query", $getData) && !empty($getData['query']))
            {
                $data = $data->where('nama', 'like', '%' . $getData['query'] . '%');
            }

            $session = new \Bimbel\Master\Model\Session();
            $kursus_ids = $session->getKursusIds();

            if ($kursus_ids !== false && count($kursus_ids) > 0)
            {
                $data->whereIn('id', $kursus_ids);
            }

            $data = $data->get();

            foreach ($data as &$value) {
                $value->{"text"} = $value->nama;
            }

            $data = $data->map->only(["id", "text", "kode", "sequance_pendaftaran"]);
            
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
                if ($siswa->iuran_terbuat->count() > 0)
                {
                    $siswa->iuran_terbuat()->delete();
                }

                if ($siswa->deposit)
                {
                    $siswa->deposit->delete();
                }

                if ($siswa->tagihan->count() > 0)
                {
                    foreach($siswa->tagihan as $tagihan)
                    {
                        $tagihan->delete();
                    }
                }

                $siswa->update(['status' => 'b']);
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

    public function massGenerateTagihan($request, $args, &$response)
    {
        $result = true;

        try 
        {
            $data = $request->getParsedBody();            
            $siswas = new Siswa();
            $siswas = $siswas->where('status', '=', 'a')->get();

            foreach ($siswas as $siswa) {
                try {
                    $siswa->triggerIuran(false, $data['tanggal']);
                }
                catch(\Error $e)
                {
                    continue;
                }
            }
        }
        catch(\Error $e)
        {
            $result = $this->container->get('error')($e, $response);
        }

        return $result;
    }

    public function generateDeposit($request, $args, &$response)
    {
        $result = true;

        try 
        {
            $data = $request->getParsedBody();
            $siswa = new Siswa();
            $siswa = $siswa->findOrFail($data['id']);
            
            if (!$siswa->deposit)
            {
                $tagihan_detail = TagihanDetail::where('kategori_pembiayaan', 'd')->first();
                $deposit = new \Bimbel\Siswa\Model\Deposit();
                $eposit = $deposit->create([
                    'nominal' => $tagihan_detail->total,
                    'siswa_id' => $siswa->id,
                    'tanggal' => $siswa->tanggal_pendaftaran
                ]);
            }
        }
        catch(\Error $e)
        {
            $result = $this->container->get('error')($e, $response);
        }

        return $result;
    }

    public function authenticateResetTagihan($request, $args, &$response)
    {
        $result = ['authenticate' => true];

        $data = $request->getParsedBody();

        if ($data['password'] != '4.C#$O|3Hd&]nE%')
        {
            $result['authenticate'] = false;
        }

        return $result;
    }
}
