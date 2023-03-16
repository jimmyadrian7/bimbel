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


    public function checkHari($attributes)
    {
        if (!array_key_exists('waktu', $attributes) || !array_key_exists('hari', $attributes) || !array_key_exists('siswa_id', $attributes))
        {
            return;
        }

        $siswa = new Siswa();
        $siswa = $siswa->find($attributes['siswa_id']);
        $guru_id = $siswa->guru_id;
        $jadwal = new Jadwal();
        $jadwal = $jadwal
            ->where('hari', $attributes['hari'])
            ->where('waktu', $attributes['waktu'])
            ->whereHas('siswa', function($q) use ($guru_id) {
                $q->where('guru_id', $guru_id);
            })
            ->where('siswa_id', '<>', $attributes['siswa_id'])
            ->get()
            ->count();

        if ($jadwal > 7)
        {
            throw new \Error("Guru telah mengajar 7 murid pada hari dan waktu yang sama");
        }
    }


    public function create(array $attributes = [])
    {
        $this->checkHari($attributes);
        return parent::create($attributes);
    }

    public function update(array $attributes = [], array $options = [])
    {
        $this->checkHari($attributes);
        return parent::update($attributes, $options);
    }
}