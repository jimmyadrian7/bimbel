<?php

namespace Bimbel\User\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Master\Model\Orang;
use Bimbel\User\Model\Role;

class User extends BaseModel
{
    protected $fillable = ['username', 'password', 'unenpass', 'jenis_user', 'orang_id', 'orang', 'status'];
	protected $table = 'user';
	protected $hidden = ['password'];
	// protected $with  = ['role', 'orang'];

	protected $status_enum = [
        ["value" => "s", "label" => "Super Admin"],
        ["value" => "c", "label" => "Admin Cabang"],
        ["value" => "u", "label" => "User"]
    ];


	public function orang()
	{
		return $this->hasOne(Orang::class, 'id', 'orang_id');
	}
	public function role()
	{
		return $this->belongsToMany(Role::class, 'user_role', 'user_id', 'role_id');
	}

	public static function encrypt($value)
	{
		$value = password_hash($value, PASSWORD_DEFAULT);
		return $value;
	}

    public function handleOrang(&$attributes)
    {
        if (array_key_exists('orang', $attributes))
        {
            $static = !(isset($this) && $this instanceof self);
            $orang = new Orang();
            
            if ($static)
            {
                $orang = $orang->create($attributes['orang']);
                $attributes['orang_id'] = $orang->id;
            }
            else if($this->orang_id != null && trim($this->orang_id) != '')
            {
                $orang = $this->orang;
                $orang->update($attributes['orang']);
            }
            else
            {
                $orang = $orang->create($attributes['orang']);
                $attributes['orang_id'] = $orang->id;
            }
            unset($attributes['orang']);
        }
    }
	public function handleCabang($attributes)
	{
		$jenis_user = self::getValue($attributes, 'jenis_user', false);

		if (empty($jenis_user))
		{
			return;
		}

		if ($jenis_user == 'c')
		{
			$role = new Role();
			$role = $role->where('kode', 'C')->first();
			$is_exists = $this->role()->where('role.id', $role->id)->exists();

			if (!$is_exists)
			{
				$this->role()->attach([$role->id]);
			}
		}
		else
		{
			$role = new Role();
			$role = $role->where('kode', 'C')->first();
			$is_exists = $this->role()->where('role.id', $role->id)->exists();
			
			if ($is_exists)
			{
				$this->role()->detach([$role->id]);
			}
		}
	}

	public function create(array $attributes = [])
	{
		if (array_key_exists('pass', $attributes)) {
			$attributes['password'] = $attributes['pass'];
			unset($attributes['pass']);
		}

		if (!array_key_exists('password', $attributes)) {
			$created_pass = bin2hex(random_bytes(4));
			$attributes['password'] = $created_pass;
		}

		if (!array_key_exists('username', $attributes)) {
			$username = bin2hex(random_bytes(3));
			$attributes['username'] = $username;
		}

		if (!array_key_exists('orang', $attributes) && !array_key_exists('orang_id', $attributes)) {
			$attributes['orang'] = [
				'nama' => $attributes['username']
			];
		}

		$attributes['unenpass'] = $attributes['password'];
		$attributes['password'] = self::encrypt($attributes['password']);		

        self::handleOrang($attributes);
		return parent::create($attributes);
	}

	public function update($attributes = [], $options = [])
	{
		if (array_key_exists('pass', $attributes)) {
			$attributes['password'] = $attributes['pass'];
			unset($attributes['pass']);
		}

		if (array_key_exists('password', $attributes))
		{
			$attributes['unenpass'] = $attributes['password'];
			$attributes['password'] = self::encrypt($attributes['password']);
		}

        $this->handleOrang($attributes);
        $this->handleCabang($attributes);
		return parent::update($attributes, $options);
	}

	public function delete()
	{
		$this->role()->detach();
		return parent::delete();
	}


	public function fetchAllData($condition, $obj, $pagination = false, $page = 1, $sort = [])
    {
        $obj = $this->with('orang');

		foreach($condition as $key => $con)
        {
            if ($con[0] == 'nama')
            {
                $obj->whereHas('orang', function($q) use ($con) {
                    $q->where([$con]);
                });
                unset($condition[$key]);
            }
        }

        return parent::fetchAllData($condition, $obj, $pagination, $page, $sort);
    }

	public function fetchDetail($id, $obj)
    {
		$obj = $obj->with('orang', 'role');
        $data = parent::fetchDetail($id, $obj);
        $data->deleteable = false;

        return $data;
    }
}