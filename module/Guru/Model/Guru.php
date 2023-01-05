<?php
namespace Bimbel\Guru\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Master\Model\Orang;
use Bimbel\Siswa\Model\Siswa;
use Bimbel\Pengeluaran\Model\Gaji;
use Bimbel\Guru\Model\TunjanganGuru;
use Bimbel\Master\Model\File;


class Guru extends BaseModel
{
    protected $fillable = [
        'orang_id', 'status', 'orang',
        'berhenti', 'memilih', 'kelebihan', 'kekurangan', 'kesehatan', 'lingkungan', 'aturan', 'pelatihan', 'kapan',
        'gaji_sebelumnya', 'gaji_diminta', 'ideal', 'rekaman', 'rekaman_id'

    ];
    protected $table = 'guru';
    protected $with = ['orang', 'gaji', 'tunjangan_guru', 'rekaman'];

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
    public function rekaman()
    {
        return $this->hasOne(File::class, 'id', 'rekaman_id');
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
    public function handleFile(&$attributes)
    {
        $file = new File();
        $isCreate = true;
        $rekaman = self::getValue($attributes, 'rekaman');

        if (empty($rekaman))
        {
            return;
        }

        if (!empty($this->rekaman_id))
        {
            $file = $this->rekaman;
            $isCreate = false;
        }

        if ($isCreate)
        {
            $file = $file->create($rekaman);
        }
        else
        {
            $file->update($rekaman);
        }

        $attributes['rekaman_id'] = $file->id;
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
        self::handleFile($attributes);
		$guru = parent::create($attributes);

        $guru->createuser();

        return $guru;
    }

    public function update(array $attributes = [], array $options = [])
    {
        $this->handleOrang($attributes);
        $this->handleFile($attributes);
        return parent::update($attributes, $options);
    }
}