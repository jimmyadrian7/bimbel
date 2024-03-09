<?php
namespace Bimbel\FixData\Controller;

use \Bimbel\Master\Controller\Controller;
use \Slim\Exception\HttpNotFoundException;
use Illuminate\Database\Capsule\Manager as DB;
use \Bimbel\FixData\Model\Utils;

class Patch2Controller extends Controller
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

                $table->foreign('kursus_id')->references('id')->on('kursus');
            });
        }

        // Create Table cicil modal
        if (!DB::getSchemaBuilder()->hasTable('cicil_modal'))
        {
            DB::getSchemaBuilder()->create('cicil_modal', function ($table) {
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
}
