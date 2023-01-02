<?php
namespace Bimbel\Pembayaran\Model;

use Bimbel\Core\Model\BaseModel;

class Pembiayaan extends BaseModel
{
    protected $fillable = ['kategori_pembiayaan', 'kode', 'nama', 'harga', 'stok', 'jumlah_stok', 'komisi'];
    protected $table = 'pembiayaan';

    protected $kategori_pembiayaan_enum = [
        ["value" => "a", "label" => "Aksesoris"],
        ["value" => "s", "label" => "SPP"],
        ["value" => "p", "label" => "Pendaftaran"],
        ["value" => "d", "label" => "Deposit"],
        ["value" => "l", "label" => "Lain-lain"]
    ];
}