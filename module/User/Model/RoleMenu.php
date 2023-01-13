<?php
namespace Bimbel\User\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\User\Model\Role;
use Bimbel\Master\Model\Menu;

class RoleMenu extends BaseModel
{
    protected $fillable = ['role_id', 'menu_id', 'create', 'update', 'delete'];
    protected $table = 'role_menu';

    
    public function role()
	{
		return $this->hasOne(Role::class, 'id', 'role_id');
	}
    public function menu()
	{
		return $this->hasOne(Menu::class, 'id', 'menu_id');
	}
}
