<?php
namespace Bimbel\Web\Model;

use Bimbel\Core\Model\BaseModel;

class UserQuestion extends BaseModel
{
    protected $fillable = ['name', 'email', 'phone', 'subject', 'message'];
    protected $table = 'user_question';
}
