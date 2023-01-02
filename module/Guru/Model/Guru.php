<?php
namespace Bimbel\Guru\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Master\Model\Orang;
use Bimbel\Siswa\Model\Siswa;
use Bimbel\Pengeluaran\Model\Gaji;
use Bimbel\Guru\Model\TunjanganGuru;


class Guru extends BaseModel
{
    protected $fillable = ['orang_id', 'status', 'orang'];
    protected $table = 'guru';
    protected $with = ['orang', 'gaji', 'tunjangan_guru'];

    protected $status_enum = [
        ["value" => "a", "label" => "Aktif"],
        ["value" => "n", "label" => "Nonaktif"]
    ];


    public function orang()
    {
        return $this->hasOne(Orang::class, 'id', 'orang_id');
    }
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'guru_id', 'id');
    }
    public function gaji()
    {
        return $this->hasMany(Gaji::class, 'guru_id', 'id');
    }
    public function tunjangan_guru()
    {
        return $this->hasMany(TunjanganGuru::class, 'guru_id', 'id');
    }

    
    public function handleOrang(&$attributes)
    {
        $orang_value = self::getValue($attributes, 'orang');

        if (empty($orang_value))
        {
            return;
        }

        $isCreate = true;
        $orang = new Orang();

        if (!empty($this->orang_id))
        {
            $orang = $this->orang;
            $isCreate = false;
        }

        if ($isCreate)
        {
            $orang = $orang->create($orang_value);
        }
        else
        {
            $orang->update($orang_value);
        }

        $attributes['orang_id'] = $orang->id;
    }

    public function createuser()
    {
        $user = new \Bimbel\User\Model\User();
        $user = $user->create([
            "username" => $this->orang->nama,
            "orang_id" => $this->orang_id
        ]);

        $role = new \Bimbel\User\Model\Role();
        $role = $role->where('kode', 'G')->first(); // G for Guru

        $user->role()->attach([$role->id]);
    }


    public function create(array $attributes = [])
    {
        self::handleOrang($attributes);
		$guru = parent::create($attributes);

        $guru->createuser();

        return $guru;
    }

    public function update(array $attributes = [], array $options = [])
    {
        $this->handleOrang($attributes);
        return parent::update($attributes, $options);
    }
}