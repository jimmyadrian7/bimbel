<?php

namespace Bimbel\Pengeluaran\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Pengeluaran\Model\CicilanModal;
use Bimbel\Pengeluaran\Model\TarikModal;
use Bimbel\Master\Model\Kursus;

class Modal extends BaseModel 
{
    protected $fillable = ['nominal', 'keterangan', 'status', 'kursus_id', 'sisa'];
    protected $table = 'modal';

    protected $status_enum = [
        ["value" => "a", "label" => "Aktif"],
        ["value" => "c", "label" => "Cicil"],
        ["value" => "l", "label" => "Lunas"]
    ];

    public function cicilan_modal()
    {
        return $this->hasMany(CicilanModal::class, 'modal_id', 'id');
    }
    public function kursus()
    {
        return $this->hasOne(Kursus::class, 'id', 'kursus_id');
    }
    public function tarik_modal()
    {
        return $this->hasMany(TarikModal::class, 'modal_id', 'id');
    }


    public function validateData($attributes)
    {
        if (!array_key_exists('kursus_id', $attributes))
        {
            if (!$this->kursus_id)
            {
                throw new \Error("Data tidak valid");
            }

            $attributes['kursus_id'] = $this->kursus_id;
        }

        $isExists = $this->where('kursus_id', $attributes['kursus_id'])->where('id', '<>', $this->id)->exists();

        if ($isExists)
        {
            throw new \Error("Modal dengan kursus ini sudah ada");
        }

        if (!array_key_exists('nominal', $attributes))
        {
            if (!$this->nominal)
            {
                throw new \Error("Harap isi nominal");
            }

            $attributes['nominal'] = $this->nominal;
        }
    }

    public function getSisa(&$attributes)
    {
        if ($this->status == 'a')
        {
            if (array_key_exists('nominal', $attributes))
            {
                $attributes['sisa'] = $attributes['nominal'];
            }
        }
    }


    public function create(array $attributes = [])
    {
        $this->validateData($attributes);
        $attributes['sisa'] = $attributes['nominal'];
        return parent::create($attributes);
    }

    public function update(array $attributes = [], array $options = [])
    {
        $this->validateData($attributes);
        $this->getSisa($attributes);
        return parent::update($attributes, $options);
    }

    public function fetchDetail($id, $obj)
    {
        $obj = $obj->with('cicilan_modal', 'cicilan_modal.bukti', 'tarik_modal', 'tarik_modal.bukti', 'kursus');
        $data = parent::fetchDetail($id, $obj);
        
        if ($data->status != 'a')
        {
            $data->editable = false;
            $data->deleteable = false;
        }

        return $data;
    }
}