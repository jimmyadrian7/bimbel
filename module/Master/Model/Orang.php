<?php
namespace Bimbel\Master\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Master\Model\Agama;
use Bimbel\Master\Model\File;

class Orang extends BaseModel
{
    protected $fillable = [
        'nama', 'nama_mandarin', 'jenis_kelamin', 'agama_id', 'alamat', 'email', 'tempat_lahir', 'tanggal_lahir',
        'hobi', 'no_hp', 'nama_ayah', 'nama_ibu', 'no_hp_ortu', 'pekerjaan_ayah', 'pekerjaan_ibu',
        'pp', 'pp_id'
    ];
    protected $table = 'orang';

    protected $jenis_kelamin_enum = [
        ["value" => "l", "label" => "Laki-laki"],
        ["value" => "p", "label" => "Perempuan"]
    ];

    public function agama()
    {
        return $this->hasOne(Agama::class, 'id', 'agama_id');
    }
    public function pp()
    {
        return $this->hasOne(File::class, 'id', 'pp_id');
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


    public function create(array $attributes = [])
    {
        self::handleFile($attributes, 'pp');
		$orang = parent::create($attributes);

        return $orang;
    }

    public function update(array $attributes = [], array $options = [])
    {
        $this->handleFile($attributes, 'pp');        
        $result = parent::update($attributes, $options);

        return $result;
    }

    public function delete()
    {
        $result = parent::delete();
        $this->pp()->delete();

        return $result;
    }


    public function fetchDetail($id, $obj)
    {
        $obj = $obj->with('pp');
        $data = parent::fetchDetail($id, $obj);

        return $data;
    }
}
