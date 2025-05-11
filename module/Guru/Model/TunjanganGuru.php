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


    public function format($tanggal, $jenis_pembayaran)
    {
        $tanggal_gaji = new \DateTime($tanggal);
        $tanggal_gaji = $tanggal_gaji->format("d/m/Y");

        return [
            "nama_siswa" => "-",
            "kode_tagihan" => "-",
            "tanggal_tagihan" => $tanggal_gaji,
            "tanggal_lunas" => $tanggal_gaji,
            "nama_item" => $this->nama,
            "harga_total" => $this->nominal * $this->jumlah,
            "komisi" => $this->nominal * $this->jumlah,
            "jenis_pembayaran" => $jenis_pembayaran,
        ];
    }
}
