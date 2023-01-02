<?php

namespace Bimbel\Pengeluaran\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Pengeluaran\Model\CicilanAset;

class TabunganAset extends BaseModel 
{
    protected $fillable = ['nama', 'jumlah', 'harga', 'total', 'sisa', 'keterangan', 'cicil', 'status'];
    protected $table = 'tabungan_aset';
    protected $with = ['cicilan_aset'];

    protected $status_enum = [
        ["value" => "a", "label" => "Aktif"],
        ["value" => "c", "label" => "Cicil"],
        ["value" => "l", "label" => "Lunas"]
    ];


    public function cicilan_aset()
    {
        return $this->hasMany(CicilanAset::class, 'tabungan_aset_id', 'id');
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


    public function create(array $attributes = [])
    {
        self::hitungTotal($attributes);
        return parent::create($attributes);
    }

    public function update(array $attributes = [], array $options = [])
    {
        $this->hitungTotal($attributes);
        return parent::update($attributes, $options);
    }
}