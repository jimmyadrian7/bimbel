<?php
namespace Bimbel\Web\Model;

use Bimbel\Core\Model\BaseModel;

class Kontak extends BaseModel
{
    protected $fillable = ['nama', 'email', 'subject', 'message'];
    protected $table = 'kontak';
}
