<?php
namespace Bimbel\Web\Model;

use Bimbel\Core\Model\BaseModel;

class KonfigurasiWeb extends BaseModel
{
    protected $fillable = ['lokasi', 'email', 'gmap', 'no_hp', 'facebook', 'whatsapp', 'instagram'];
    protected $table = 'konfigurasi_web';

    public function fetchDetail($id, $obj)
    {
        $data = parent::fetchDetail($id, $obj);
        $data->deleteable = false;
        return $data;
    }
}
