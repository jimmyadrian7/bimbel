<?php
namespace Bimbel\Master\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Master\Model\Agama;

class Orang extends BaseModel
{
    protected $fillable = [
        'nama', 'nama_mandarin', 'jenis_kelamin', 'agama_id', 'alamat', 'email', 'tempat_lahir', 'tanggal_lahir',
        'hobi', 'no_hp', 'nama_ayah', 'nama_ibu', 'no_hp_ortu', 'pekerjaan_ayah', 'pekerjaan_ibu'
    ];
    protected $table = 'orang';

    protected $jenis_kelamin_enum = [
        ["value" => "l", "label" => "Laki-laki"],
        ["value" => "p", "label" => "Perempuan"]
    ];

    public function agama()
    {
        return $this->hasOne(Agama::class, 'id', 'agama_id');
    }
}
