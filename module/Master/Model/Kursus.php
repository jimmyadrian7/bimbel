<?php
namespace Bimbel\Master\Model;

use Bimbel\Core\Model\BaseModel;
use Illuminate\Database\Capsule\Manager as DB;

class Kursus extends BaseModel
{
    protected $fillable = ['kode', 'nama', 'user', 'sequance', 'sequance_pendaftaran'];
    protected $table = 'kursus';


    public function fetchDetail($id, $obj)
    {
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
}
