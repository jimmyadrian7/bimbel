<?php
namespace Bimbel\Master\Model;

use Bimbel\Core\Model\BaseModel;

class Menu extends BaseModel
{
    protected $fillable = ['kode', 'nama', 'parent'];
    protected $table = 'menu';
}
