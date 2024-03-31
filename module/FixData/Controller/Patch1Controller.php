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
}
