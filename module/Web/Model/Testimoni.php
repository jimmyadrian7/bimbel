<?php
namespace Bimbel\Web\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Master\Model\File;

class Testimoni extends BaseModel
{
    protected $fillable = ['link', 'gambar_id', 'gambar', 'tipe'];
    protected $table = 'testimoni';
    protected $with = ['gambar'];


    protected $tipe_enum = [
        ["value" => "g", "label" => "Gambar"],
        ["value" => "l", "label" => "Link"]
    ];


    public function gambar()
	{
		return $this->hasOne(File::class, 'id', 'gambar_id');
	}

    public function handleFile(&$attributes)
    {
        if (!array_key_exists('gambar', $attributes))
        {
            return;
        }

        if (empty($attributes['gambar']))
        {
            unset($attributes['gambar']);
            return;
        }

        $gambar = $attributes['gambar'];
        $file = new File();
        $isCreate = true;

        if (array_key_exists('id', $gambar))
        {
            $file = $file->find($gambar['id']);

            if ($file)
            {
                $isCreate = false;
            }
        }

        if (!empty($this->gambar_id))
        {
            $file = $this->gambar;
            $isCreate = false;
        }

        if ($isCreate)
        {
            $file = new File();
            $file = $file->create($gambar);
        }
        else
        {
            $file->update($gambar);
        }

        $attributes['gambar_id'] = $file->id;
        unset($attributes['gambar']);
    }

    public function create(array $attributes = [])
    {        
        $this->handleFile($attributes);
        return parent::create($attributes);
    }

    public function update(array $attributes = [], array $options = [])
    {
        $this->handleFile($attributes);
        return parent::update($attributes, $options);
    }

    public function delete()
    {
        $result = parent::delete();
        $this->gambar()->delete();
        return $result;
    }
}
