<?php
namespace Bimbel\FixData\Controller;

use \Bimbel\Master\Controller\Controller;
use \Slim\Exception\HttpNotFoundException;
use Illuminate\Database\Capsule\Manager as DB;
use \Bimbel\FixData\Model\Utils;

class Patch1Controller extends Controller
{
    public function patch3($request, $args, &$response)
    {
        $result = ["data" => "success"];

        // Create Table modal
        if (!DB::getSchemaBuilder()->hasTable('modal'))
        {
            DB::getSchemaBuilder()->create('modal', function ($table) {
                $table->increments('id');
                $table->integer('kursus_id');
                $table->text('keterangan');
                $table->integer('nominal');
                $table->integer('sisa');
                $table->enum('status', ['a', 'c', 'l'])->default('a');

                $table->foreign('kursus_id')->references('id')->on('kursus');
            });
        }

        // Create Table cicil modal
        if (!DB::getSchemaBuilder()->hasTable('cicilan_modal'))
        {
            DB::getSchemaBuilder()->create('cicilan_modal', function ($table) {
                $table->increments('id');
                $table->unsignedInteger('modal_id');
                $table->integer('nominal');
                $table->integer('pengeluaran_id')->nullable();
                $table->integer('bukti_id')->nullable();
                $table->enum('status', ['m', 's'])->default('m');
                $table->date('tanggal');

                $table->foreign('modal_id')->references('id')->on('modal');
                $table->foreign('bukti_id')->references('id')->on('file');
                $table->foreign('pengeluaran_id')->references('id')->on('pengeluaran');
            });
        }

        // Create Table tarik modal
        if (!DB::getSchemaBuilder()->hasTable('tarik_modal'))
        {
            DB::getSchemaBuilder()->create('tarik_modal', function ($table) {
                $table->increments('id');
                $table->unsignedInteger('modal_id');
                $table->integer('nominal');
                $table->integer('bukti_id')->nullable();
                $table->enum('status', ['m', 's'])->default('m');
                $table->date('tanggal');

                $table->foreign('modal_id')->references('id')->on('modal');
                $table->foreign('bukti_id')->references('id')->on('file');
            });
        }

        Utils::addMenuReport('modal_report', 'Modal');
        Utils::addMenuReport('modal', 'Modal', 'pengeluaran');

        return $result;
    }

    public function patch4($request, $args, &$response)
    {
        $result = ["data" => "success"];

        $isColExist = DB::getSchemaBuilder()->getConnection()->getSchemaBuilder()->hasColumn('orang','no_rek_ortu');
        if (!$isColExist)
        {
            DB::getSchemaBuilder()->getConnection()->getSchemaBuilder()->table("orang", function($table) {
                $table->string('no_rek_ortu', 255)->nullable()->after('pekerjaan_ibu');
            });
        }

        return $result;
    }

    public function patch5($request, $args, &$response)
    {
        $result = ["data" => "success"];

        Utils::addMenuReport('siswa_utang', 'Siswa Utang');

        return $result;
    }

    public function patch6($request, $args, &$response)
    {
        $result = ["data" => "success"];

        $tempat_kursus = new \Bimbel\Master\Model\Kursus();
        $isColExist = $tempat_kursus->getConnection()->getSchemaBuilder()->hasColumn('kursus','no_rek');
        if (!$isColExist)
        {
            $tempat_kursus->getConnection()->getSchemaBuilder()->table("kursus", function($table) {
                $table->string('no_rek', 255)->nullable()->after('sequance_pendaftaran');
            });
        }

        $tempat_kursus = new \Bimbel\Master\Model\Kursus();
        $isColExist = $tempat_kursus->getConnection()->getSchemaBuilder()->hasColumn('kursus','nama_rek');
        if (!$isColExist)
        {
            $tempat_kursus->getConnection()->getSchemaBuilder()->table("kursus", function($table) {
                $table->string('nama_rek', 255)->nullable()->after('no_rek');
            });
        }

        $tempat_kursus = new \Bimbel\Master\Model\Kursus();
        $isColExist = $tempat_kursus->getConnection()->getSchemaBuilder()->hasColumn('kursus','logo_bank_id');
        if (!$isColExist)
        {
            $tempat_kursus->getConnection()->getSchemaBuilder()->table("kursus", function($table) {
                $table->integer('logo_bank_id')->nullable()->after('nama_rek');
            });
        }

        $tagihan = new \Bimbel\Pembayaran\Model\Tagihan();
        $isColExist = $tagihan->getConnection()->getSchemaBuilder()->hasColumn('tagihan','program_belajar');
        if (!$isColExist)
        {
            $tagihan->getConnection()->getSchemaBuilder()->table("tagihan", function($table) {
                $table->string('program_belajar', 255)->nullable();
            });
        }

        $tagihan = new \Bimbel\Pembayaran\Model\Tagihan();
        $isColExist = $tagihan->getConnection()->getSchemaBuilder()->hasColumn('tagihan','jenis_pembayaran');
        if (!$isColExist)
        {
            $tagihan->getConnection()->getSchemaBuilder()->table("tagihan", function($table) {
                $table->string('jenis_pembayaran', 255)->nullable();
            });
        }

        $tagihan = new \Bimbel\Pembayaran\Model\Tagihan();
        $isColExist = $tagihan->getConnection()->getSchemaBuilder()->hasColumn('tagihan','terima_dari');
        if (!$isColExist)
        {
            $tagihan->getConnection()->getSchemaBuilder()->table("tagihan", function($table) {
                $table->string('terima_dari', 255)->nullable();
            });
        }

        $tagihan = new \Bimbel\Pembayaran\Model\Tagihan();
        $isColExist = $tagihan->getConnection()->getSchemaBuilder()->hasColumn('tagihan','keterangan');
        if (!$isColExist)
        {
            $tagihan->getConnection()->getSchemaBuilder()->table("tagihan", function($table) {
                $table->string('keterangan', 255)->nullable();
            });
        }

        return $result;
    }

    public function patch7($request, $args, &$response)
    {
        $result = ["data" => "success"];

        // Create Table potongan
        if (!DB::getSchemaBuilder()->hasTable('potongan_gaji'))
        {
            DB::getSchemaBuilder()->create('potongan_gaji', function ($table) {
                $table->increments('id');
                $table->integer('guru_id');
                $table->date('tanggal');
                $table->integer('nominal');

                $table->foreign('guru_id')->references('id')->on('guru');
            });
        }

        return $result;
    }

    public function patch8($request, $args, &$response)
    {
        $result = ["data" => "success"];

        $potongan_gaji = new \Bimbel\Guru\Model\PotonganGaji();
        $isColExist = $potongan_gaji->getConnection()->getSchemaBuilder()->hasColumn('potongan_gaji','asisten_guru');
        if (!$isColExist)
        {
            $potongan_gaji->getConnection()->getSchemaBuilder()->table("potongan_gaji", function($table) {
                $table->string('asisten_guru', 255)->nullable()->after('nominal');
            });
        }

        $guru = new \Bimbel\Guru\Model\Guru();
        $isColExist = $guru->getConnection()->getSchemaBuilder()->hasColumn('guru','jabatan');
        if (!$isColExist)
        {
            $guru->getConnection()->getSchemaBuilder()->table("guru", function($table) {
                $table->string('jabatan', 255)->nullable()->after('no_rek');
            });
        }

        $kursus = new \Bimbel\Master\Model\Kursus();
        $isColExist = $kursus->getConnection()->getSchemaBuilder()->hasColumn('kursus','diserahkan_oleh');
        if (!$isColExist)
        {
            $kursus->getConnection()->getSchemaBuilder()->table("kursus", function($table) {
                $table->string('diserahkan_oleh', 255)->nullable()->after('logo_bank_id');
            });
        }

        $kursus = new \Bimbel\Master\Model\Kursus();
        $isColExist = $kursus->getConnection()->getSchemaBuilder()->hasColumn('kursus','diketahui_oleh');
        if (!$isColExist)
        {
            $kursus->getConnection()->getSchemaBuilder()->table("kursus", function($table) {
                $table->string('diketahui_oleh', 255)->nullable()->after('diserahkan_oleh');
            });
        }

        $kursus = new \Bimbel\Master\Model\Kursus();
        $isColExist = $kursus->getConnection()->getSchemaBuilder()->hasColumn('kursus','diterima_oleh');
        if (!$isColExist)
        {
            $kursus->getConnection()->getSchemaBuilder()->table("kursus", function($table) {
                $table->string('diterima_oleh', 255)->nullable()->after('diketahui_oleh');
            });
        }

        $tunjangan_guru = new \Bimbel\Guru\Model\TunjanganGuru();
        $isColExist = $tunjangan_guru->getConnection()->getSchemaBuilder()->hasColumn('tunjangan_guru','jumlah');
        if (!$isColExist)
        {
            $tunjangan_guru->getConnection()->getSchemaBuilder()->table("tunjangan_guru", function($table) {
                $table->string('jumlah', 255)->nullable()->after('nominal');
            });
        }

        return $result;
    }
}
