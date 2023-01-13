<?php
namespace Bimbel\User\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\User\Model\RoleMenu;

class Role extends BaseModel
{
    protected $fillable = ['kode', 'nama', 'role_menu'];
    protected $table = 'role';

    
    public function role_menu()
	{
		return $this->hasMany(RoleMenu::class, 'role_id', 'id');
	}


    public function handleMenu($menus)
    {
        if (empty($menus))
        {
            return;
        }

        $menu_ids = [];

        foreach($menus as $menu)
        {
            $menu_obj = new RoleMenu();
            $menu['role_id'] = $this->id;

            if (array_key_exists('id', $menu))
            {
                $menu_obj = $menu_obj->find($menu['id']);
                $menu_obj->update($menu);
            }
            else
            {
                $menu_obj = $menu_obj->create($menu);
            }
            array_push($menu_ids, $menu_obj->id);
        }

        $menu_obj = new RoleMenu();
        $menu_obj = $menu_obj->whereNotIn('id', $menu_ids)->where('role_id', $this->id);
        $menu_obj->delete();
    }

    public function create(array $attributes = [])
	{
        $menus = self::getValue($attributes, 'role_menu');
		$role = parent::create($attributes);
        $role->handleMenu($menus);

        return $role;
	}

    public function update(array $attributes = [], array $options = [])
    {
        $menus = self::getValue($attributes, 'role_menu');
        $result = parent::update($attributes, $options);
        $this->handleMenu($menus);

        return $result;
    }

    public function fetchDetail($id, $obj)
    {
        $obj = $obj->with('role_menu', 'role_menu.menu');
        $data = parent::fetchDetail($id, $obj);
        $data->deleteable = false;

        return $data;
    }
}
