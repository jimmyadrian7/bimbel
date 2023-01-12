<?php
namespace Bimbel\User\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\User\Model\RoleMenu;

class Role extends BaseModel
{
    protected $fillable = ['kode', 'nama'];
    protected $table = 'role';

    
    public function role_menu()
	{
		return $this->hasMany(RoleMenu::class, 'id', 'role_id');
	}
}
