<?php
namespace Bimbel\Pembayaran\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Pembayaran\Model\IuranDetail;

class Iuran extends BaseModel
{
    protected $fillable = [
        'nama', 'bulan', 'iuran_detail'
    ];
    protected $table = 'iuran';
    protected $with = ['iuran_detail'];

    
    public function iuran_detail()
    {
        return $this->hasMany(IuranDetail::class, 'iuran_id', 'id');
    }


    public function handleIuranDetail($iuran_details)
    {
        $iuran_id = $this->id;
        $iuran_detail_ids = [];

        if (count($iuran_details) === 0)
        {
            return;
        }

        foreach($iuran_details as $iuran_detail_value)
        {
            $iuran_detail = new IuranDetail();
            $isCreate = true;

            $iuran_detail_value['iuran_id'] = $iuran_id;
            
            if (array_key_exists('id', $iuran_detail_value))
            {
                $iuran_detail = $iuran_detail->find($iuran_detail_value['id']);
                if($iuran_detail)
                {
                    $isCreate = false;
                }
                unset($iuran_detail_value['id']);
            }

            if ($isCreate)
            {
                $iuran_detail = new IuranDetail();
                $iuran_detail = $iuran_detail->create($iuran_detail_value);
            }
            else
            {
                $iuran_detail->update($iuran_detail_value);
            }

            array_push($iuran_detail_ids, $iuran_detail->id);
        }
        
        $iuran_detail = new IuranDetail();
        $iuran_detail
            ->where('iuran_id', $iuran_id)
            ->whereNotIn('id', $iuran_detail_ids)
            ->delete();
    }

    public function create(array $attributes = [])
    {
        $iuran_details = self::getValue($attributes, 'iuran_detail');
		$tagihan = parent::create($attributes);
        
        $tagihan->handleIuranDetail($iuran_details);

        return $tagihan;
    }

    public function update(array $attributes = [], array $options = [])
    {
        $iuran_details = self::getValue($attributes, 'iuran_detail');
        $this->handleIuranDetail($iuran_details);

        return parent::update($attributes, $options);
    }
}