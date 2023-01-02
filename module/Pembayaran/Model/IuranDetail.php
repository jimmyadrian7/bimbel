<?php
namespace Bimbel\Pembayaran\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Pembayaran\Model\Pembiayaan;

class IuranDetail extends BaseModel
{
    protected $fillable = ['iuran_id', 'pembiayaan_id', 'skip', 'qty', 'total'];
    protected $table = 'iuran_detail';
    protected $with = ['pembiayaan'];

    public function pembiayaan()
    {
        return $this->hasOne(Pembiayaan::class, 'id', 'pembiayaan_id');
    }
}