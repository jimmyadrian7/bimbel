<?php
namespace Bimbel\Master\Model;

use Bimbel\Core\Model\BaseModel;
use Illuminate\Database\Capsule\Manager as DB;

class ReportInfo extends BaseModel
{
    protected $fillable = ['logo_id', 'alamat', 'no_hp', 'email', 'logo'];
    protected $with = ['logo'];
    protected $table = 'report_info';

    public function logo()
    {
        return $this->belongsTo(File::class, 'logo_id');
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
        self::handleFile($attributes, 'logo');
		$kursus = parent::create($attributes);

        return $kursus;
    }

    public function update(array $attributes = [], array $options = [])
    {
        $this->handleFile($attributes, 'logo');
        $result = parent::update($attributes, $options);

        return $result;
    }

    public function delete()
    {
        $result = parent::delete();
        $this->logo()->delete();

        return $result;
    }


    public function fetchDetail($id, $obj)
    {
        $data = parent::fetchDetail($id, $obj);
        $data->deleteable = false;
        return $data;
    }
}
