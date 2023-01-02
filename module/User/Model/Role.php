<?php
namespace Bimbel\User\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Master\Model\Menu;

class Role extends BaseModel
{
    protected $fillable = ['kode', 'nama'];
    protected $table = 'role';
    protected $with = ['menu'];

    
    public function menu()
	{
		return $this->belongsToMany(Menu::class, 'role_menu', 'role_id', 'menu_id');
	}
}
