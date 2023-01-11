<?php
namespace Bimbel\Pengeluaran\Model;

use Bimbel\Core\Model\BaseModel;

class Pengeluaran extends BaseModel
{
    protected $fillable = ['nama', 'jumlah', 'harga', 'total', 'gaji', 'aset', 'tanggal'];
    protected $table = 'pengeluaran';

    public function hitungTotal(&$attributes)
    {
        if (!array_key_exists('harga', $attributes) || !array_key_exists('jumlah', $attributes))
        {
            throw new \Error("data is not valid");
        }

        $attributes['total'] = $attributes['harga'] * $attributes['jumlah'];
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

    public function fetchDetail($id, $obj)
    {
        $data = parent::fetchDetail($id, $obj);
        
        if ($data->aset || $data->gaji)
        {
            $data->editable = false;
            $data->deleteable = false;
        }

        return $data;
    }
}
