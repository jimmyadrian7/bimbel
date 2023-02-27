<?php
namespace Bimbel\Guru\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Master\Model\Orang;
use Bimbel\Siswa\Model\Siswa;
use Bimbel\Pengeluaran\Model\Gaji;
use Bimbel\Guru\Model\TunjanganGuru;
use Bimbel\Master\Model\File;
use Bimbel\Master\Model\Kursus;


class Guru extends BaseModel
{
    protected $fillable = [
        'orang_id', 'status', 'orang',
        'berhenti', 'memilih', 'kelebihan', 'kekurangan', 'kesehatan', 'lingkungan', 'aturan', 'pelatihan', 'kapan',
        'gaji_sebelumnya', 'gaji_diminta', 'ideal', 'rekaman', 'rekaman_id', 'pp', 'pp_id', 'kursus',
        'nama_bank', 'no_rek'

    ];
    protected $table = 'guru';

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
    public function kursus()
    {
        return $this->belongsToMany(Kursus::class, 'guru_kursus', 'guru_id', 'kursus_id');
    }
    public function rekaman()
    {
        return $this->hasOne(File::class, 'id', 'rekaman_id');
    }
    public function pp()
    {
        return $this->hasOne(File::class, 'id', 'pp_id');
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
    public function handleFile(&$attributes, $name)
    {
        $file = new File();
        $isCreate = true;
        $myFile = self::getValue($attributes, $name);

        if (empty($myFile))
        {
            return;
        }

        if (!empty($this->{$name . "_id"}))
        {
            $file = $this->{$name};
            $isCreate = false;
        }

        if ($isCreate)
        {
            $file = $file->create($myFile);
        }
        else
        {
            $file->update($myFile);
        }

        $attributes[$name . '_id'] = $file->id;
    }
    public function handleStatus($attributes)
    {
        $status = self::getValue($attributes, 'status');

        if (empty($status))
        {
            return;
        }

        if ($status == 'n')
        {
            $user = new \Bimbel\User\Model\User();
            $user = $user->where("orang_id", $this->orang_id)->first();
            $user->update(['status' => 'n']);
        }
        if ($status == 'a')
        {
            $user = new \Bimbel\User\Model\User();
            $user = $user->where("orang_id", $this->orang_id)->first();
            $user->update(['status' => 'a']);
        }
    }
    public function handleKursus($kursuss)
    {
        if (empty($kursuss))
        {
            return;
        }

        $kursus_ids = [];

        foreach($kursuss as $kursus)
        {
            if (!array_key_exists('id', $kursus))
            {
                continue;
            }

            array_push($kursus_ids, $kursus['id']);
            $isExists = $this->kursus()->where('kursus.id', $kursus['id'])->exists();

            if ($isExists)
            {
                continue;
            }

            $this->kursus()->attach([$kursus['id']]);
        }

        $kursus_unids = $this->kursus()->whereNotIn('kursus.id', $kursus_ids)->get()->pluck('id');
        $this->kursus()->detach($kursus_unids);
    }

    public function createuser()
    {
        $user = new \Bimbel\User\Model\User();
        $user = $user->create([
            // "username" => $this->orang->nama,
            "orang_id" => $this->orang_id
        ]);

        $role = new \Bimbel\User\Model\Role();
        $role = $role->where('kode', 'G')->first(); // G for Guru

        $user->role()->attach([$role->id]);
    }


    public function create(array $attributes = [])
    {
        $kursus = self::getValue($attributes, 'kursus');
        self::handleOrang($attributes);
        self::handleFile($attributes, 'rekaman');
        self::handleFile($attributes, 'pp');
		$guru = parent::create($attributes);

        $guru->createuser();
        $guru->handleKursus($kursus);

        return $guru;
    }

    public function update(array $attributes = [], array $options = [])
    {
        $kursus = self::getValue($attributes, 'kursus');
        $this->handleOrang($attributes);
        $this->handleFile($attributes, 'rekaman');
        $this->handleFile($attributes, 'pp');
        $this->handleStatus($attributes);
        
        $result = parent::update($attributes, $options);
        $this->handleKursus($kursus);

        return $result;
    }

    public function delete()
    {
        $this->tunjangan_guru()->delete();
        $this->rekaman()->delete();
        
        $user = new \Bimbel\User\Model\User();
        $user = $user->where('orang_id', $this->orang_id)->first();
        $user->delete();

        $result = parent::delete();
        $this->orang()->delete();
        $this->rekaman()->delete();
        $this->pp()->delete();
        $this->kursus()->delete();

        return $result;
    }


    public function fetchAllData($condition, $obj, $pagination = false, $page = 1)
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

        return parent::fetchAllData($condition, $obj, $pagination, $page);
    }

    public function fetchDetail($id, $obj)
    {
        $obj = $obj->with('orang', 'gaji', 'tunjangan_guru', 'rekaman', 'orang.pp', 'kursus', 'siswa', 'siswa.orang');
        $data = parent::fetchDetail($id, $obj);
        
        if ($data->status != 'a')
        {
            $data->editable = false;
        }

        if ($data->siswa->count() > 0)
        {
            $data->deleteable = false;
        }

        $user = new \Bimbel\User\Model\User();
        $user = $user->where('orang_id', $data->orang_id)->first();
        $data->user = $user;

        return $data;
    }
}