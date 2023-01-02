<?php
namespace Bimbel\Pengeluaran\Model;

use Bimbel\Core\Model\BaseModel;

class Aset extends BaseModel
{
    protected $fillable = ['nama', 'tanggal_beli', 'kondisi', 'jumlah', 'harga'];
    protected $table = 'aset';
}
