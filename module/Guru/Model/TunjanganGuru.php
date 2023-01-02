<?php
namespace Bimbel\Guru\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Guru\Model\Guru;

class TunjanganGuru extends BaseModel
{
    protected $fillable = ['guru_id', 'nama', 'nominal'];
    protected $table = 'tunjangan_guru';

    public function guru()
    {
        return $this->hasOne(Guru::class, 'id', 'guru_id');
    }
}
