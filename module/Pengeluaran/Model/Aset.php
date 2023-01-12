<?php
namespace Bimbel\Pengeluaran\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Master\Model\Kursus;

class Aset extends BaseModel
{
    protected $fillable = ['nama', 'tanggal_beli', 'kondisi', 'jumlah', 'harga', 'kursus_id'];
    protected $table = 'aset';


    public function kursus()
    {
        return $this->hasOne(Kursus::class, 'id', 'kursus_id');
    }
}
