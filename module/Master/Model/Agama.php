<?php
namespace Bimbel\Master\Model;

use Bimbel\Core\Model\BaseModel;

class Agama extends BaseModel
{
    protected $fillable = ['kode', 'nama'];
    protected $table = 'agama';
}
