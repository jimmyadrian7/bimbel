<?php
namespace Bimbel\Guru\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Guru\Model\Guru;

class PotonganGaji extends BaseModel
{
    protected $fillable = ['guru_id', 'tanggal', 'nominal', 'asisten_guru'];
    protected $table = 'potongan_gaji';

    public function guru()
    {
        return $this->hasOne(Guru::class, 'id', 'guru_id');
    }

}
