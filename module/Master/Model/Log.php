<?php
namespace Bimbel\Master\Model;

use Bimbel\Core\Model\BaseModel;

class Log extends BaseModel
{
    protected $fillable = ['target_id', 'target_table', 'operation', 'data'];
    protected $table = 'log';
}
