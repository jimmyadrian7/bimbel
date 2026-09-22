<?php
namespace Bimbel\Master\Model;

use Bimbel\Core\Model\BaseModel;
use Illuminate\Database\Capsule\Manager as DB;

class HistoryGeneratedTagihan extends BaseModel
{
    protected $fillable = ['siswa_id', 'generated', 'remark', 'created_at'];
    protected $table = 'history_generated_tagihan';


    public function fetchDetail($id, $obj)
    {
        $data = parent::fetchDetail($id, $obj);
        $data->deleteable = false;

        return $data;
    }
}
