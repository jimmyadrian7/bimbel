<?php

namespace Bimbel\Pengeluaran\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Pengeluaran\Model\CicilanAset;
use Bimbel\Pengeluaran\Model\Penarikan;
use Bimbel\Master\Model\Kursus;

class TabunganAset extends BaseModel 
{
    protected $fillable = ['code', 'nama', 'jumlah', 'harga', 'total', 'sisa', 'keterangan', 'cicil', 'status', 'kursus_id'];
    protected $table = 'tabungan_aset';

    protected $status_enum = [
        ["value" => "a", "label" => "Aktif"],
        ["value" => "c", "label" => "Cicil"],
        ["value" => "l", "label" => "Lunas"]
    ];


    public function cicilan_aset()
    {
        return $this->hasMany(CicilanAset::class, 'tabungan_aset_id', 'id');
    }
    public function kursus()
    {
        return $this->hasOne(Kursus::class, 'id', 'kursus_id');
    }
    public function penarikan()
    {
        return $this->hasMany(Penarikan::class, 'tabungan_aset_id', 'id');
    }

    
    public function hitungTotal(&$attributes)
    {
        if (!array_key_exists('jumlah', $attributes) || !array_key_exists('harga', $attributes))
        {
            return;
        }
        
        $attributes['total'] = $attributes['jumlah'] * $attributes['harga'];
        $attributes['sisa'] = $attributes['total'];
    }

    public function getSisa($exclude_id = false)
    {
        $sisa = $this->total;

        foreach($this->cicilan_aset as $cicilan_aset)
        {
            if ($exclude_id && $exclude_id == $cicilan_aset->id)
            {
                continue;    
            }

            $sisa -= $cicilan_aset->nominal;
        }

        return $sisa;
    }


    public function create(array $attributes = [])
    {
        $seq = new \Bimbel\Master\Model\Sequance();
        $attributes['code'] = $seq->getnextCode('tabungan_aset');

        self::hitungTotal($attributes);
        return parent::create($attributes);
    }

    public function update(array $attributes = [], array $options = [])
    {
        $this->hitungTotal($attributes);
        return parent::update($attributes, $options);
    }

    public function fetchDetail($id, $obj)
    {
        $obj = $obj->with('cicilan_aset', 'penarikan');
        $data = parent::fetchDetail($id, $obj);
        
        if ($data->status != 'a')
        {
            $data->editable = false;
            $data->deleteable = false;
        }

        return $data;
    }
}