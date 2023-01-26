<?php
namespace Bimbel\Master\Model;

use \Bimbel\Core\Model\Session as SessionMaster;
use \Bimbel\User\Model\User;

class Session extends SessionMaster 
{
    private $data = false;

    function __construct()
    {
        $user_id = $this->get('user_id');
        $data = new User();
        $data = $data->with('orang')->find($user_id);
        if (!$data)
        {
            throw new \Error("You are currently not logged in", 501);
        }

        $this->data = $data;
    }

    public function getCurrentUser()
    {
        $user = $this->data;
        $user->offsetUnset('password');
        $user->offsetUnset('unenpass');

        return $user;
    }

    public function isJenis($jenis)
    {
        $result = false;
        if ($this->data->jenis_user == $jenis)
        {
            $result = true;
        }

        return $result;
    }

    public function isSuperUser()
    {
        return $this->isJenis("s");
    }

    public function isCabang()
    {
        return $this->isJenis("c");
    }

    public function isRole($kode)
    {
        $result = count($this->data->role->where('kode', $kode)) === 1 ? true : false;
        return $result;
    }

    public function getKursusIds()
    {
        $guru = new \Bimbel\Guru\Model\Guru();
        $guru = $guru->where('orang_id', $this->data->orang_id)->first();
        $kursus_ids = $guru->kursus->pluck('id');

        return $kursus_ids->toArray();
    }
    
    public function getGuruIds()
    {
        $kursus_ids = $this->getKursusIds();
        $guru = new \Bimbel\Guru\Model\Guru();
        $guru = $guru->whereHas('kursus', function($q) use ($kursus_ids) {
            $q->whereIn('kursus.id', $kursus_ids);
        })->where('orang_id', '<>', $this->data->orang_id);
        $guru_ids = $guru->pluck('id');
        
        return $guru_ids->toArray();
    }

    public function getSiswaIds()
    {
        $result = false;
        $siswa_ids = [];
        
        if ($this->isSuperUser())
        {
            $siswa_ids = false;
        }
        else if ($this->isCabang())
        {
            $kursus_ids = $this->getKursusIds();
            $siswa = new \Bimbel\Siswa\Model\Siswa();
            $siswa = $siswa->whereIn('kursus_id', $kursus_ids)->get();
            $siswa_ids = $siswa->pluck('id')->toArray();
        }
        else if ($this->isRole('G'))
        {
            $guru = new \Bimbel\Guru\Model\Guru();
            $guru = $guru->where('orang_id', $this->data->orang_id)->first();
            $siswa_ids = $guru->siswa->pluck('id')->toArray();
        }
        else if ($this->isRole('S'))
        {
            $siswa = new \Bimbel\Siswa\Model\Siswa();
            $siswa = $siswa->where('orang_id', $this->data->orang_id)->first();
            $siswa_ids = [$siswa->id];
        }

        return $siswa_ids;
    }
}