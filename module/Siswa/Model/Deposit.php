<?php
namespace Bimbel\Siswa\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Master\Model\File;
use Bimbel\Siswa\Model\Siswa;

class Deposit extends BaseModel
{
    protected $fillable = ['tanggal', 'nominal', 'siswa_id', 'status', 'bukti_pembayaran', 'bukti_pembayaran_id'];
    protected $table = 'deposit';
    // protected $with = ['bukti_pembayaran', 'siswa'];

    protected $status_enum = [
        ["value" => "a", "label" => "Aktif"],
        ["value" => "t", "label" => "Terima"],
        ["value" => "h", "label" => "Hangus"]
    ];

    
    public function bukti_pembayaran()
    {
        return $this->hasOne(File::class, 'id', 'bukti_pembayaran_id');
    }
    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'id', 'siswa_id');
    }


    public function handleFile(&$attributes)
    {
        $file = new File();
        $isCreate = true;
        $bukti_pembayaran = self::getValue($attributes, 'bukti_pembayaran');

        if (empty($bukti_pembayaran))
        {
            return;
        }

        if (!empty($this->bukti_pembayaran_id))
        {
            $file = $this->bukti_pembayaran;
            $isCreate = false;
        }

        if ($isCreate)
        {
            $file = $file->create($bukti_pembayaran);
        }
        else
        {
            $file->update($bukti_pembayaran);
        }

        $attributes['bukti_pembayaran_id'] = $file->id;
    }

    public function handleStatus($attributes)
    {
        if (!array_key_exists('status', $attributes) || $attributes['status'] != 't')
        {
            return;
        }

        $this->siswa->update(['status' => 'h']);
    }


    public function create(array $attributes = [])
    {
        self::handleFile($attributes);
        return parent::create($attributes);
    }

    public function update(array $attributes = [], array $options = [])
    {
        $this->handleFile($attributes);
        $this->handleStatus($attributes);

        return parent::update($attributes, $options);
    }


    public function fetchAllData($condition, $obj, $pagination = false, $page = 1, $sort = [])
    {
        $obj = $this->with('siswa', 'siswa.orang', 'bukti_pembayaran');

        foreach($condition as $key => $con)
        {
            if ($con[0] == 'nama')
            {
                $obj = $obj->whereHas('siswa', function($query) use ($con) {
                    $query->whereHas('orang', function($q) use ($con) {
                        $q->where([$con]);
                    });
                });
                unset($condition[$key]);
            }
        }

        return parent::fetchAllData($condition, $obj, $pagination, $page, $sort);
    }

    public function fetchDetail($id, $obj)
    {
        $obj = $obj->with('siswa', 'siswa.orang', 'bukti_pembayaran');
        $data = parent::fetchDetail($id, $obj);

        // $data->editable = false;
        // $data->deleteable = false;

        return $data;
    }
}