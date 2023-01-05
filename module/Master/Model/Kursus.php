<?php
namespace Bimbel\Master\Model;

use Bimbel\Core\Model\BaseModel;

class Kursus extends BaseModel
{
    protected $fillable = ['kode', 'nama'];
    protected $table = 'kursus';
}
