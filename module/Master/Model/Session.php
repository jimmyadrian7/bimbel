<?php
namespace Bimbel\Master\Model;

use \Bimbel\Core\Model\Session as SessionMaster;


class Session extends SessionMaster 
{
    private $data = false;

    function __construct()
    {
        $data = $this->get('user');
        if (!$data)
        {
            throw new \Error("Session not found");
        }

        $this->data = $data;
    }

    public function isSuperUser()
    {
        $result = false;
        if ($this->data->super_user)
        {
            $result = true;
        }

        return $result;
    }

    public function isRole($kode)
    {
        $result = count($this->data->role->where('kode', $kode)) === 1 ? true : false;
        return $result;
    }

    public function getSiswaIds()
    {
        $result = false;
        $siswa_ids = [];
        
        if ($this->isSuperUser())
        {
            $siswa_ids = false;
        }
        else if ($this->isRole('G'))
        {
            $guru = new \Bimbel\Guru\Model\Guru();
            $guru = $guru->where('orang_id', $this->data->orang_id)->first();
            $siswa_ids = $guru->siswa->pluck('id');
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