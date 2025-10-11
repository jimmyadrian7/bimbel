<?php
namespace Bimbel\FixData\Controller;

use \Bimbel\Master\Controller\Controller;
use \Slim\Exception\HttpNotFoundException;
use Illuminate\Database\Capsule\Manager as DB;
use \Bimbel\FixData\Model\Utils;

class Patch2Controller extends Controller
{
    public function patch1($request, $args, &$response)
    {
        $result = ["data" => "success"];

        // Create Table Asisten Guru
        if (!DB::getSchemaBuilder()->hasTable('asisten_guru'))
        {
            DB::getSchemaBuilder()->create('asisten_guru', function ($table) {
                $table->increments('id');
                $table->integer('orang_id')->nullable();
                $table->enum('status', ['a', 'n', 'd'])->default('a')->nullable();
                $table->integer('pp_id')->nullable();
                $table->text('berhenti')->nullable();
                $table->text('memilih')->nullable();
                $table->text('kelebihan')->nullable();
                $table->text('kekurangan')->nullable();
                $table->text('kesehatan')->nullable();
                $table->text('lingkungan')->nullable();
                $table->text('aturan')->nullable();
                $table->text('pelatihan')->nullable();
                $table->text('kapan')->nullable();
                $table->integer('gaji_sebelumnya')->nullable();
                $table->integer('gaji_diminta')->nullable();
                $table->integer('gaji_tetap')->nullable();
                $table->integer('rekaman_id')->nullable();
                $table->text('ideal')->nullable();
                $table->string('nama_bank', 255);
                $table->string('no_rek', 255);
                $table->string('jabatan', 255)->nullable();

                $table->foreign('orang_id')->references('id')->on('orang');
                $table->foreign('pp_id')->references('id')->on('file');
                $table->foreign('rekaman_id')->references('id')->on('file');
            });
        }

        // Create Table Asisten Guru Kursus
        if (!DB::getSchemaBuilder()->hasTable('asisten_guru_kursus'))
        {
            DB::getSchemaBuilder()->create('asisten_guru_kursus', function ($table) {
                $table->increments('id');
                $table->integer('asisten_guru_id')->nullable();
                $table->integer('kursus_id')->nullable();

                $table->foreign('asisten_guru_id')->references('id')->on('asisten_guru');
                $table->foreign('kursus_id')->references('id')->on('kursus');
            });
        }

        $isColExist = DB::getSchemaBuilder()->getConnection()->getSchemaBuilder()->hasColumn('tunjangan_guru','asisten_guru_id');
        if (!$isColExist)
        {
            DB::getSchemaBuilder()->getConnection()->getSchemaBuilder()->table("tunjangan_guru", function($table) {
                $table->integer('asisten_guru_id')->nullable()->after('guru_id');
            });
        }

        $isColExist = DB::getSchemaBuilder()->getConnection()->getSchemaBuilder()->hasColumn('potongan_gaji','asisten_guru_id');
        if (!$isColExist)
        {
            DB::getSchemaBuilder()->getConnection()->getSchemaBuilder()->table("potongan_gaji", function($table) {
                $table->integer('asisten_guru_id')->nullable()->after('guru_id');
            });
        }

        // Add Role
        $user = \Bimbel\User\Model\Role::firstOrCreate(['kode' => 'AG', 'nama' => 'Asisten Guru']);

        $menu_guru_id = Utils::addMenuReport('guru', 'Guru', 'guru');
        $menu_asisten_guru_id = Utils::addMenuReport('asisten_guru', 'Asisten Guru', 'guru');

        Utils::updateAccessRight('cud', $menu_guru_id, [1, 4]);
        Utils::updateAccessRight('cud', $menu_asisten_guru_id, [1, 4]);
        Utils::updateAccessRight('cud', '6', [1, 4]);

        return $result;
    }

    public function patch2($request, $args, &$response)
    {
        $result = ["data" => "success"];

        $isColExist = DB::getSchemaBuilder()->getConnection()->getSchemaBuilder()->hasColumn('kursus','diserahkan_oleh_file_id');
        if (!$isColExist)
        {
            DB::getSchemaBuilder()->getConnection()->getSchemaBuilder()->table("kursus", function($table) {
                $table->integer('diserahkan_oleh_file_id')->nullable()->after('diterima_oleh');
            });
        }
        $isColExist = DB::getSchemaBuilder()->getConnection()->getSchemaBuilder()->hasColumn('kursus','diketahui_oleh_file_id');
        if (!$isColExist)
        {
            DB::getSchemaBuilder()->getConnection()->getSchemaBuilder()->table("kursus", function($table) {
                $table->integer('diketahui_oleh_file_id')->nullable()->after('diserahkan_oleh_file_id');
            });
        }
        $isColExist = DB::getSchemaBuilder()->getConnection()->getSchemaBuilder()->hasColumn('kursus','diterima_oleh_file_id');
        if (!$isColExist)
        {
            DB::getSchemaBuilder()->getConnection()->getSchemaBuilder()->table("kursus", function($table) {
                $table->integer('diterima_oleh_file_id')->nullable()->after('diketahui_oleh_file_id');
            });
        }

        return $result;
    }

    public function patch3($request, $args, &$response)
    {
        $result = ["data" => "success"];

        // Create Table Report Info
        if (!DB::getSchemaBuilder()->hasTable('report_info'))
        {
            DB::getSchemaBuilder()->create('report_info', function ($table) {
                $table->increments('id');
                $table->integer('logo_id')->nullable();
                $table->text('alamat')->nullable();
                $table->string('no_hp', 255)->nullable();
                $table->string('email', 255)->nullable();

                $table->foreign('logo_id')->references('id')->on('file');
            });

            DB::table('report_info')->insert(['id' => 1]);
        }

        $menu_guru_id = Utils::addMenuReport('report_profile', 'Report Profile', 'konfigurasi');
        Utils::updateAccessRight('cud', $menu_guru_id, [1, 4]);

        return $result;
    }

    public function patch4($request, $args, &$response)
    {
        $result = ["data" => "success"];

        $isColExist = DB::getSchemaBuilder()->getConnection()->getSchemaBuilder()->hasColumn('siswa','status_siswa');
        if (!$isColExist)
        {
            DB::getSchemaBuilder()->getConnection()->getSchemaBuilder()->table("siswa", function($table) {
                $table->enum('status_siswa', ['Pelajar', 'Mahasiswa', 'Pekerja'])->nullable();
            });
        }

        $tagihan = new \Bimbel\Pembayaran\Model\Tagihan();
        $isColExist = $tagihan->getConnection()->getSchemaBuilder()->hasColumn('tagihan','untuk_pembayaran');
        if (!$isColExist)
        {
            $tagihan->getConnection()->getSchemaBuilder()->table("tagihan", function($table) {
                $table->string('untuk_pembayaran', 255)->nullable()->after('terima_dari');
            });
        }

        return $result;
    }
}
