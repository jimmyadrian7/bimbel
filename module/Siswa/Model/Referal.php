<?php
namespace Bimbel\Siswa\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Siswa\Model\Siswa;

class Referal extends BaseModel
{
    protected $fillable = ['siswa_id', 'nama'];
    protected $table = 'referal';


    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'id', 'siswa_id');
    }
}