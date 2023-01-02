<?php
namespace Bimbel\Siswa\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Siswa\Model\Siswa;

class Jadwal extends BaseModel
{
    protected $fillable = ['siswa_id', 'hari', 'waktu'];
    protected $table = 'jadwal';

    protected $hari_enum = [
        ["value" => "1", "label" => "Senin"],
        ["value" => "2", "label" => "Selasa"],
        ["value" => "3", "label" => "Rabu"],
        ["value" => "4", "label" => "Kamis"],
        ["value" => "5", "label" => "Jumat"],
        ["value" => "6", "label" => "Sabtu"],
        ["value" => "7", "label" => "Minggu"]
    ];

    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'id', 'siswa_id');
    }
}