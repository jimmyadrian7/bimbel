<?php
namespace Bimbel\Master\Model;

use Bimbel\Core\Model\BaseModel;

class File extends BaseModel
{
    protected $fillable = ['filename', 'filetype', 'base64'];
    protected $table = 'file';
}
