<?php
namespace Bimbel\Guru\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Guru\Model\Guru;

class TunjanganGuru extends BaseModel
{
    protected $fillable = ['guru_id', 'nama', 'nominal', 'jumlah'];
    protected $table = 'tunjangan_guru';

    public function guru()
    {
        return $this->hasOne(Guru::class, 'id', 'guru_id');
    }


    public function format()
    {
        return [
            "nama_siswa" => "-",
            "kode_tagihan" => "-",
            "tanggal_tagihan" => "-",
            "tanggal_lunas" => "-",
            "nama_item" => $this->nama,
            "harga_total" => $this->nominal,
            "komisi" => $this->nominal
        ];
    }
}
