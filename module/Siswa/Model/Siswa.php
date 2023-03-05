<?php
namespace Bimbel\Siswa\Model;

use Bimbel\Core\Model\BaseModel;

use Bimbel\Master\Model\Orang;
use Bimbel\Master\Model\Kursus;
use Bimbel\Guru\Model\Guru;

use Bimbel\Siswa\Model\Deposit;
use Bimbel\Siswa\Model\IuranTerbuat;
use Bimbel\Siswa\Model\Jadwal;
use Bimbel\Siswa\Model\Referal;

use Bimbel\Pembayaran\Model\Iuran;
use Bimbel\Pembayaran\Model\Tagihan;

class Siswa extends BaseModel
{
    protected $fillable = [
        'no_formulir', 'status', 'tanggal_pendaftaran', 'guru_id', 'komisi',
        'orang_id', 'orang',
        'iuran', 'jadwal', 'ref',
        'pinyin', 'dengar', 'bicara', 'membaca', 'menulis', 'kondisi', 'respon', 'tanggapan',
        'program', 'paket_belajar', 'referal_other',
        'kursus_id', 'sekolah', 'kelas'
    ];
    protected $table = 'siswa';

    protected $status_enum = [
        ["value" => "b", "label" => "Baru"],
        ["value" => "a", "label" => "Aktif"],
        ["value" => "p", "label" => "Pengembalian"],
        ["value" => "n", "label" => "Berhenti"]
    ];

    protected $appends = ['guru_data', 'ref'];
    public function getGuruDataAttribute()
    {
		$guru_id = [
            "id" => $this->guru_id,
            "nama" => $this->guru->orang->nama
        ];

        

		return $guru_id;
	}

    public function getRefAttribute()
    {
        $ref = [];

        foreach($this->referal as $value)
        {
            $ref[$value->id] = true;
        }

        return $ref;
    }


    public function orang()
    {
        return $this->hasOne(Orang::class, 'id', 'orang_id');
    }
    public function tagihan()
    {
        return $this->hasMany(Tagihan::class, 'siswa_id', 'id');
    }
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id', 'id');
    }
    public function iuran()
    {
        return $this->belongsToMany(Iuran::class, 'siswa_iuran', 'siswa_id', 'iuran_id');
    }
    public function iuran_terbuat()
    {
        return $this->hasMany(IuranTerbuat::class, 'siswa_id', 'id');
    }
    public function deposit()
    {
        return $this->hasOne(Deposit::class, 'siswa_id', 'id');
    }
    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'siswa_id', 'id');
    }
    public function referal()
    {
        return $this->belongsToMany(Referal::class, 'siswa_referal', 'siswa_id', 'referal_id');
    }
    public function kursus()
    {
        return $this->hasOne(Kursus::class, 'id', 'kursus_id');
    }


    public function getSequance(&$attributes)
    {
        $seq = new \Bimbel\Master\Model\Sequance();
        $attributes['no_formulir'] = $seq->getnextCode('pendaftaran');
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


    public function recreateIuran()
    {
        foreach($this->iuran as $iuran)
        {
            $iuran_terbuat = new IuranTerbuat();
            $iuran_terbuat = $iuran_terbuat->create([
                'siswa_id' => $this->id,
                'iuran_id' => $iuran->id
            ]);
        }
    }

    /*
        @id iuran id
        @action add | edit | delete
        @old_id required if edit only
    */
    public function handleIuran($iurans)
    {
        $siswa = $this;

        if (empty($iurans))
        {
            return;
        }

        foreach($iurans as $iuran)
        {
            if (!array_key_exists('action', $iuran) || !array_key_exists('id', $iuran))
            {
                continue;
            }

            switch ($iuran['action']) {
                case 'add':
                    $this->iuran()->attach([$iuran['id']]);
                    $iuran_terbuat = new IuranTerbuat();
                    $iuran_terbuat = $iuran_terbuat->create([
                        'siswa_id' => $siswa->id,
                        'iuran_id' => $iuran['id']
                    ]);
                    break;
                case 'edit':
                    $this->iuran()->attach([$iuran['id']]);
                    $this->iuran()->detach([$iuran['old_id']]);

                    $iuran_terbuat = new IuranTerbuat();
                    $iuran_terbuat = $iuran_terbuat->where('iuran_id', $iuran['old_id'])->where('siswa_id', $this->id);
                    $iuran_terbuat->update(['iuran_id' => $iuran['id']]);
                    break;
                case 'delete':
                    $this->iuran()->detach([$iuran['id']]);
                    
                    $iuran_terbuat = new IuranTerbuat();
                    $iuran_terbuat = $iuran_terbuat->where('iuran_id', $iuran['id'])->where('siswa_id', $this->id);
                    $iuran_terbuat->delete();
                    break;
            }
        }
    }

    public function handleJadwal(&$jadwals)
    {
        $jadwal_ids = [];

        if (empty($jadwals))
        {
            return;
        }

        foreach($jadwals as $jadwal_value)
        {
            $jadwal = new Jadwal();

            $jadwal_value['siswa_id'] = $this->id;

            if (array_key_exists('id', $jadwal_value))
            {
                $jadwal = $jadwal->find($jadwal_value['id']);
                $jadwal->update($jadwal_value);
            }
            else
            {
                $jadwal = $jadwal->create($jadwal_value);
            }

            array_push($jadwal_ids, $jadwal->id);
        }

        $jadwal = new Jadwal();
        $jadwal = $jadwal->where('siswa_id', $this->id)->whereNotIn('id', $jadwal_ids);
        $jadwal->delete();
    }
    public function handleRef($refs)
    {
        $siswa = $this;
        $ref_ids = [];
        $inst_ids = [];

        if (empty($refs))
        {
            return;
        }

        foreach($refs as $key => $value)
        {
            if ($value)
            {
                $hasRef = $siswa->referal()->where('referal.id', $key)->exists();

                if (!$hasRef)
                {
                    array_push($inst_ids, $key);
                }
                array_push($ref_ids, $key);
            }
        }

        if (count($inst_ids) > 0)
        {
            $siswa->referal()->attach($inst_ids);
        }

        $ref_unids = $siswa->referal()->whereNotIn('referal.id', $ref_ids)->get()->pluck('id');
        $siswa->referal()->detach($ref_unids);
    }


    public function createUser()
    {
        $user = new \Bimbel\User\Model\User();
        $user = $user->where("orang_id", $this->orang_id);

        if ($user->count() > 0)
        {
            return;
        }

        $user = new \Bimbel\User\Model\User();
        $user = $user->create([
            // "username" => $this->orang->nama,
            "orang_id" => $this->orang_id
        ]);

        $role = new \Bimbel\User\Model\Role();
        $role = $role->where('kode', 'S')->first(); // S for Siswa

        $user->role()->attach([$role->id]);
    }
    public function cekDeposit()
    {
        $status = "p";        
        $pendaftaran = new \DateTime($this->tanggal_pendaftaran);
        $datenow = new \DateTime("now");
        $interval = $pendaftaran->diff($datenow);

        if ($interval->y == 0)
        {
            $status = 'n';
            $this->deposit()->update(['status' => 'h']);
        }

        return $status;
    }
    public function handleStatus(&$attributes)
    {
        if (!array_key_exists('status', $attributes))
        {
            return;
        }

        switch($attributes['status'])
        {
            case "p":
                $attributes['status'] = $this->cekDeposit();
            break;
            
            case "a":
                $this->createUser();
            break;
        }
    }
    

    public function triggerIuran($firstTime = false, $tanggal = false)
    {
        $tagihan = new Tagihan();
        $tagihan_detail = [];

        foreach ($this->iuran_terbuat as $iuran_terbuat)
        {
            if (!$iuran_terbuat->validateDate())
            {
                continue;
            }

            $td = $iuran_terbuat->getTagihanDetail($firstTime, $tanggal);
            $tagihan_detail = array_merge($tagihan_detail, $td);
            if (count($td) > 0)
            {
                $iuran_terbuat->updateDate($tanggal);
            }
        }

        if (count($tagihan_detail) === 0)
        {
            throw new \Error("Tagihan is empty");
        }

        $tagihan_value = [
            "siswa_id" => $this->id,
            "tagihan_detail" => $tagihan_detail
        ];

        if($tanggal)
        {
            $tagihan_value['tanggal'] = $tanggal;
        }

        $tagihan = $tagihan->create($tagihan_value);

        return $tagihan;
    }


    public function create(array $attributes = [])
    {
        $iurans = self::getValue($attributes, 'iuran');
        $jadwals = self::getValue($attributes, 'jadwal');
        $refs = self::getValue($attributes, 'ref');

        self::handleOrang($attributes);
        // self::getSequance($attributes);

		$siswa = parent::create($attributes);
        $siswa->handleJadwal($jadwals);
        $siswa->handleRef($refs);
        $siswa->handleIuran($iurans);
        $siswa->triggerIuran(true);

        return $siswa;
    }

    public function update(array $attributes = [], array $options = [])
    {
        $iurans = self::getValue($attributes, 'iuran');
        $jadwals = self::getValue($attributes, 'jadwal');
        $refs = self::getValue($attributes, 'ref');
        
        $this->handleOrang($attributes);
        $this->handleStatus($attributes);

        $result = parent::update($attributes, $options);

        $this->handleJadwal($jadwals);
        $this->handleRef($refs);
        $this->handleIuran($iurans);

        return $result;
    }

    public function delete()
    {
        // if ($this->status != 'b')
        // {
        //     throw new \Error("Siswa cannot be deleted");
        // }

        $this->iuran_terbuat()->delete();
        $this->jadwal()->delete();
        $this->iuran()->detach();
        $this->referal()->detach();
        $this->deposit()->delete();
        
        $tagihan = new Tagihan();
        $tagihans = $tagihan->where('siswa_id', $this->id)->get();

        foreach ($tagihans as $tagihan)
        {
            $tagihan->delete();
        }

        $result = parent::delete();

        $user = new \Bimbel\User\Model\User();
        $user = $user->where('orang_id', $this->orang_id)->first();

        $log = new \Bimbel\Master\Model\Log();

        $log->where('user_id', $user->id)->delete();
        $user->delete();
        $this->orang()->delete();

        return $result;
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
        $obj = $obj->with('orang', 'iuran', 'jadwal', 'orang.pp');
        $data = parent::fetchDetail($id, $obj);

        // if ($data->status != 'b')
        // {
        //     $data->deleteable = false;
        // }

        $user = new \Bimbel\User\Model\User();
        $user = $user->where('orang_id', $data->orang_id)->first();
        $data->user = $user;

        return $data;
    }
}
