<?php
namespace Bimbel\Siswa\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Siswa\Model\Siswa;
use Illuminate\Database\Capsule\Manager as DB;

class Referal extends BaseModel
{
    protected $fillable = ['nama'];
    protected $table = 'referal';


    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'id', 'siswa_id');
    }


    public function fetchDetail($id, $obj)
    {
        $data = parent::fetchDetail($id, $obj);
        
        $query = "
            SELECT COUNT(id) AS total FROM
            (
                SELECT id FROM siswa_referal
                WHERE referal_id = %u
            ) AS x
            GROUP BY id
        ";
        $query = sprintf($query, $id);

        $total = DB::select(DB::raw($query));

        if (count($total) > 0)
        {
            $data->editable = false;
            $data->deleteable = false;
        }

        return $data;
    }
}