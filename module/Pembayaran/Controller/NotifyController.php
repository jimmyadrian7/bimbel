<?php
namespace Bimbel\Pembayaran\Controller;

use \Bimbel\Master\Controller\Controller;
use \Slim\Exception\HttpNotFoundException;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Support\Facades\Schema;

class NotifyController extends Controller
{
    public function notifyInvoice($request, $args, &$response)
    {
        try 
        {
            $tagihan_id = $args['tagihan_id'];
            $tagihan = new \Bimbel\Pembayaran\Model\Tagihan();
            $tagihan = $tagihan->find($tagihan_id);
            $siswa = $tagihan->siswa;
            
            if (empty($siswa->orang->no_hp))
            {
                throw new \Error("No Hp siswa kosong");
            }

            $configuration = new \Bimbel\Master\Model\AccountConfiguration();
            $configuration = $configuration->first();

            $nama = "";

            if ($configuration->parameter)
            {
                $nama = $siswa->orang->nama;
            }

            $wa = new \Bimbel\Whatsapp\Model\Whatsapp();
            $result = $wa->sendMessageTemplate(
                $configuration->wa_invoice_template, 
                $configuration->wa_invoice_template_language, 
                $siswa->orang->no_hp,
                $nama
            );

            if ($result['status'] != '200' && $result['status'] != '201')
            {
                $errMsg = $result['data'];
                $errMsg = json_decode($errMsg);
                throw new \Error($errMsg->error->message);
            }

            return true;
        }
        catch(\Error $e) 
        {
            $result = $this->container->get('error')($e, $response);
        }
    }

    public function fixData($request, $args, &$response)
    {
        $result = ["data" => "success"];

        try
        {
            // Fix data tagihan detail
            // $siswa = new \Bimbel\Siswa\Model\Siswa();
            // $siswas = $siswa->get();

            $tagihan_details = new \Bimbel\Pembayaran\Model\TagihanDetail();
            $tagihan_details = $tagihan_details->where('system', true)->where("komisi", ">", 0)->get();
            foreach($tagihan_details as $tagihan_detail)
            {
                $tagihan_detail_value = [
                    "system" => true,
                    "tanggal_iuran_mulai" => $tagihan_detail->tanggal_iuran_mulai,
                    "tanggal_iuran_berakhir" => $tagihan_detail->tanggal_iuran_berakhir,
                    'nominal' => $tagihan_detail->nominal,
                    'qty' => $tagihan_detail->qty,
                    'pembiayaan_id' => $tagihan_detail->pembiayaan_id,
                    'tagihan_id' => $tagihan_detail->tagihan_id,
                    'bulan' => $tagihan_detail->bulan
                ];

                $tagihan_detail->update($tagihan_detail_value);
            }

            // foreach($siswas as $siswa)
            // {
            //     foreach($siswa->iuran_terbuat as $iuran_terbuat)
            //     {
            //         if ($iuran_terbuat->tahun == NULL || $iuran_terbuat->bulan == NULL)
            //         {
            //             continue;
            //         }
                    
            //         $pembiayaan_ids = $iuran_terbuat->iuran->iuran_detail->pluck('pembiayaan_id')->toArray();

            //         $tagihan_details = new \Bimbel\Pembayaran\Model\TagihanDetail();
            //         $tagihan_details = $tagihan_details
            //             ->whereHas('tagihan', function($q) use ($siswa){
            //                 $q->where('siswa_id', $siswa->id);
            //             })
            //             ->whereIn('pembiayaan_id', $pembiayaan_ids)
            //             ->get();

            //         $tanggal_mulai = $iuran_terbuat->tahun . "-" . $iuran_terbuat->bulan . "-1";
            //         $tanggal_berakhir = new \DateTime($tanggal_mulai);
            //         $tanggal_berakhir = $tanggal_berakhir->modify("+" . ($iuran_terbuat->iuran->bulan-1) . "month");
            //         $tanggal_berakhir = $tanggal_berakhir->format("Y-n-1");
                        
            //         foreach($tagihan_details as $tagihan_detail)
            //         {
            //             $tagihan_detail_value = [
            //                 "system" => true,
            //                 "tanggal_iuran_mulai" => $tanggal_mulai,
            //                 "tanggal_iuran_berakhir" => $tanggal_berakhir,
            //                 'nominal' => $tagihan_detail->nominal,
            //                 'qty' => $tagihan_detail->qty,
            //                 'pembiayaan_id' => $tagihan_detail->pembiayaan_id,
            //                 'tagihan_id' => $tagihan_detail->tagihan_id,
            //                 'bulan' => $iuran_terbuat->iuran->bulan
            //             ];

            //             $tagihan_detail->update($tagihan_detail_value);
            //         }
            //     }
            // }

            // fix data tagihan
            // $tagihans = new \Bimbel\Pembayaran\Model\Tagihan();
            // $tagihans = $tagihans->where('status', 'l')->get();

            // foreach ($tagihans as $tagihan)
            // {
            //     $transaksi_terkakhir = $tagihan->transaksi()->where('status', 'v')->latest('tanggal')->first();
                
            //     $tagihan->update([
            //         'tanggal_lunas' => $transaksi_terkakhir->tanggal,
            //         'siswa_id' => $tagihan->siswa_id
            //     ]);
            // }
        }
        catch(\Error $e) 
        {
            $result = $this->container->get('error')($e, $response);
        }

        return $result;
    }

    public function fixDataGuru($request, $args, &$response)
    {
        $result = ["data" => "success"];

        try
        {
            $gurus = new \Bimbel\Guru\Model\Guru();
            $gurus = $gurus->get();

            foreach ($gurus as $guru)
            {
                $guru = $guru->orang->update([
                    'pp_id' => $guru->pp_id
                ]);
            }

        }
        catch(\Error $e) 
        {
            $result = $this->container->get('error')($e, $response);
        }

        return $result;
    }

    public function resetDataSiswa($request, $args, &$response)
    {
        $result = ["data" => "success"];

        try
        {
            $tagihans = new \Bimbel\Pembayaran\Model\Tagihan();
            $tagihans = $tagihans->get();

            foreach ($tagihans as $tagihan)
            {
                $tagihan->delete();
            }

            $siswas = new \Bimbel\Siswa\Model\Siswa();
            $siswas = $siswas->get();

            foreach ($siswas as $siswa)
            {
                $siswa->iuran_terbuat()->delete();
                $siswa->iuran()->detach();
                $siswa->deposit()->delete();
                $siswa->update(['status' => 'b']);
            }
        }
        catch(\Error $e) 
        {
            $result = $this->container->get('error')($e, $response);
        }

        return $result;
    }

    public function deleteDataPembayaran($request, $args, &$response)
    {
        $result = ["data" => "success"];

        try
        {
            $iurans = new \Bimbel\Pembayaran\Model\Iuran();
            $iurans = $iurans->get();

            foreach ($iurans as $iuran)
            {
                $iuran->delete();
            }

            $pembiayaans = new \Bimbel\Pembayaran\Model\Pembiayaan();
            $pembiayaans = $pembiayaans->get();

            foreach ($pembiayaans as $pembiayaan)
            {
                $pembiayaan->delete();
            }
        }
        catch(\Error $e) 
        {
            $result = $this->container->get('error')($e, $response);
        }

        return $result;
    }

    public function hapusGaji($request, $args, &$response)
    {
        $result = ["data" => "success"];

        try
        {
            $gaji = new \Bimbel\Pengeluaran\Model\Gaji();

            $gaji->truncate();
        }
        catch(\Error $e) 
        {
            $result = $this->container->get('error')($e, $response);
        }

        return $result;
    }

    public function fixTanggalIuran($request, $args, &$response)
    {
        $result = ["data" => "success"];

        try
        {
            $iuran_terbuat = new \Bimbel\Siswa\Model\IuranTerbuat();
            $iuran_terbuat = $iuran_terbuat->get();

            foreach($iuran_terbuat as $it)
            {
                $pembiayaan_ids = $it->iuran->iuran_detail->pluck("pembiayaan_id")->toArray();
                $tagihan = new \Bimbel\Pembayaran\Model\Tagihan();
                $tagihan = $tagihan
                    ->where("siswa_id", $it->siswa_id)
                    ->whereHas("tagihan_detail", function($q) use ($pembiayaan_ids) {
                        $q->whereIn('pembiayaan_id', $pembiayaan_ids);
                    })
                    ->first();

                if (!$tagihan)
                {
                    echo "iuran terbuat id: " . $it->id . PHP_EOL;
                    continue;
                }

                $tanggal = new \DateTime($tagihan->tanggal);
                $tanggal = $tanggal->format("Y-m-01");
                $tanggal = new \DateTime($tanggal);
                $tanggal->modify("+". ($it->iuran->bulan - 1) . "month");
                $tanggal = $tanggal->format("Y-n");
                $tanggal = explode("-", $tanggal);

                $update_value = [
                    "bulan" => $tanggal[1],
                    "tahun" => $tanggal[0]
                ];

                $it->update($update_value);
            }
        }
        catch(\Error $e) 
        {
            $result = $this->container->get('error')($e, $response);
        }

        return $result;
    }

    public function resetSequanceTagihan($request, $args, &$response)
    {
        $result = ["data" => "success"];

        $tagihan = new \Bimbel\Pembayaran\Model\Tagihan();
        $tagihan = $tagihan->orderBy('code', 'ASC')->get();
        $seq = new \Bimbel\Master\Model\Kursus();

        $kursus = new \Bimbel\Master\Model\Kursus();
        $kursus->whereRaw('1=1')->update(['sequance' => 0]);

        foreach($tagihan as $p)
        {
            $p->code = $seq->getnextCode($p->kursus_id);
            $p->save();
        }

        return $result;
    }

    public function fixDataTagihan($request, $args, &$response)
    {
        $result = ["data" => "success"];

        $tagihan = new \Bimbel\Pembayaran\Model\Tagihan();
        $isColExist = $tagihan->getConnection()->getSchemaBuilder()->hasColumn('tagihan','guru_id');
        if (!$isColExist)
        {
            $tagihan->getConnection()->getSchemaBuilder()->table("tagihan", function($table) {
                $table->integer('guru_id')->unsigned()->nullable()->after('tanggal_lunas');
            });
        }

        DB::statement('UPDATE tagihan SET guru_id =  (SELECT guru_id FROM siswa WHERE siswa.id = tagihan.siswa_id LIMIT 1)');

        return $result;
    }

    public function addFieldKeluarDeposit($request, $args, &$response)
    {
        $result = ["data" => "success"];

        $deposit = new \Bimbel\Siswa\Model\Deposit();
        $isColExist = $deposit->getConnection()->getSchemaBuilder()->hasColumn('deposit','tanggal_keluar');
        if (!$isColExist)
        {
            $deposit->getConnection()->getSchemaBuilder()->table("deposit", function($table) {
                $table->date('tanggal_keluar')->nullable()->after('tanggal');
            });
        }

        return $result;
    }

    public function addFieldSequancePendaftaranKursus($request, $args, &$response)
    {
        $result = ["data" => "success"];

        $kursus = new \Bimbel\Master\Model\Kursus();
        $isColExist = $kursus->getConnection()->getSchemaBuilder()->hasColumn('kursus','sequance_pendaftaran');
        if (!$isColExist)
        {
            $kursus->getConnection()->getSchemaBuilder()->table("kursus", function($table) {
                $table->integer('sequance_pendaftaran')->unsigned()->after('sequance');
            });
        }

        return $result;
    }

    public function updateNoFormulirSiswa($request, $args, &$response)
    {
        $result = ["data" => "success"];

        $siswa = new \Bimbel\Siswa\Model\Siswa();
        $siswa = $siswa->get();
        
        $kursus = new \Bimbel\Master\Model\Kursus();
        $kursus->whereRaw('1 = 1')->update(['sequance_pendaftaran' => 0]);

        foreach ($siswa as $s)
        {
            if ($s->kursus)
            {
                $kursus = $s->kursus->refresh();
                $s->update([
                    'no_formulir' => $kursus->kode . "-" . sprintf('%03d', $kursus->sequance_pendaftaran + 1),
                    'kursus_id' => $kursus->id
                ]);
            }
        }

        return $result;
    }

    public function addFieldYouTubeWeb($request, $args, &$response)
    {
        $result = ["data" => "success"];

        $konfigurasi_web = new \Bimbel\Web\Model\KonfigurasiWeb();
        $isColExist = $konfigurasi_web->getConnection()->getSchemaBuilder()->hasColumn('konfigurasi_web','youtube');
        if (!$isColExist)
        {
            $konfigurasi_web->getConnection()->getSchemaBuilder()->table("konfigurasi_web", function($table) {
                $table->text('youtube')->nullable()->after('tiktok');
            });
        }

        return $result;
    }

    public function addFieldPhoneWeb($request, $args, &$response)
    {
        $result = ["data" => "success"];

        $user_question = new \Bimbel\Web\Model\UserQuestion();
        $isColExist = $user_question->getConnection()->getSchemaBuilder()->hasColumn('user_question','phone');
        if (!$isColExist)
        {
            $user_question->getConnection()->getSchemaBuilder()->table("user_question", function($table) {
                $table->string('phone', 255)->nullable()->after('email');
            });
        }

        return $result;
    }
}
