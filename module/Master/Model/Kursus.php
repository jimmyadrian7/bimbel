<?php
namespace Bimbel\Master\Model;

use Bimbel\Core\Model\BaseModel;
use Illuminate\Database\Capsule\Manager as DB;

class Kursus extends BaseModel
{
    protected $fillable = [
        'kode', 'nama', 'user', 'sequance', 'sequance_pendaftaran', 'no_rek', 'nama_rek', 'logo_bank', 'logo_bank_id', 
        'diserahkan_oleh', 'diketahui_oleh', 'diterima_oleh'
    ];
    protected $table = 'kursus';


    public function fetchDetail($id, $obj)
    {
        $obj = $obj->with('logo_bank');
        $data = parent::fetchDetail($id, $obj);
        
        $query = "
            SELECT COUNT(id) AS total FROM
            (
                SELECT id FROM siswa
                WHERE kursus_id = %u
            ) AS x
            GROUP BY id
        ";
        $query = sprintf($query, $id);

        $total = DB::select(DB::raw($query));

        if (count($total) > 0)
        {
            // $data->editable = false;
            $data->deleteable = false;
        }

        return $data;
    }

    public function getnextCode($id)
	{
		$kursus = $this->find($id);

		if (empty($kursus))
		{
			return "-";
		}
        
        $kursus->refresh();
		$kode = $kursus->kode . "-". sprintf('%03d', $kursus->sequance + 1);
		$kursus->update(['sequance' => $kursus->sequance + 1]);

		return $kode;
	}

    public function logo_bank()
    {
        return $this->hasOne(File::class, 'id', 'logo_bank_id');
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
        self::handleFile($attributes, 'logo_bank');
		$kursus = parent::create($attributes);

        return $kursus;
    }

    public function update(array $attributes = [], array $options = [])
    {
        $this->handleFile($attributes, 'logo_bank');
        $result = parent::update($attributes, $options);

        return $result;
    }

    public function delete()
    {
        $result = parent::delete();
        $this->logo_bank()->delete();

        return $result;
    }
}
