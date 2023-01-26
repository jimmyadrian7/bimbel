<?php
namespace Bimbel\Pembayaran\Model;

use Bimbel\Core\Model\BaseModel;
use Illuminate\Database\Capsule\Manager as DB;

class Diskon extends BaseModel
{
    protected $fillable = ['diskon', 'tipe_diskon'];
    protected $table = 'diskon';
    public $searchField = "diskon";

    protected $tipe_diskon_enum = [
      ["value" => "p", "label" => "Persen"],
      ["value" => "n", "label" => "Nominal"]
    ];


    public function fetchDetail($id, $obj)
    {
        $data = parent::fetchDetail($id, $obj);
        
        $query = "
            SELECT COUNT(id) AS total FROM
            (
                SELECT id FROM tagihan_detail
                WHERE diskon_id = %u
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