<?php

namespace Bimbel\Pengeluaran\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Master\Model\File;
use Bimbel\Pengeluaran\Model\Modal;

class TarikModal extends BaseModel 
{
    protected $fillable = ['nominal', 'bukti_id', 'bukti', 'status', 'tanggal', 'modal_id'];
    protected $table = 'tarik_modal';

    protected $status_enum = [
        ["value" => "m", "label" => "Menunggu Verifikasi"],
        ["value" => "s", "label" => "Sukses"]
    ];


    public function modal()
    {
        return $this->belongsTo(Modal::class, 'modal_id', 'id');
    }
    public function bukti()
    {
        return $this->hasOne(File::class, 'id', 'bukti_id');
    }


    public function handleFile(&$attributes)
    {
        $file = new File();
        $isCreate = true;
        $bukti = self::getValue($attributes, 'bukti');

        if (empty($bukti))
        {
            return;
        }

        if (!empty($this->bukti_id))
        {
            $file = $this->bukti;
            $isCreate = false;
        }

        if ($isCreate)
        {
            $file = $file->create($bukti);
        }
        else
        {
            $file->update($bukti);
        }

        $attributes['bukti_id'] = $file->id;
    }

    public function validateData($attributes, $type)
    {
        if (!array_key_exists('modal_id', $attributes))
        {
            if (!$this->modal_id)
            {
                throw new \Error("Data tidak valid");
            }

            $attributes['modal_id'] = $this->modal_id;
        }

        if (!array_key_exists('nominal', $attributes))
        {
            if (!$this->nominal)
            {
                throw new \Error("harap isi nominal");
            }

            $attributes['nominal'] = $this->nominal;
        }

        if (!array_key_exists('tanggal', $attributes))
        {
            if (!$this->tanggal)
            {
                throw new \Error("harap isi tanggal");
            }
        }

        $modal = Modal::findOrFail($attributes['modal_id']);
        $tabungan = $modal->nominal - $modal->sisa;

        if ($tabungan < $attributes['nominal'])
        {
            throw new \Error("Tabungan tidak cukup");
        }
    }

    public function handleStatus($attributes)
    {
        if (array_key_exists('status', $attributes) && $attributes['status'] == 's')
        {
            $this->modal->update(['sisa' => $this->nominal + $this->modal->sisa, 'status' => 'c']);
        }
    }


    public function create(array $attributes = [])
    {
        $this->validateData($attributes, 'c');
        $this->handleFile($attributes);

		$penarikan = parent::create($attributes);
        $penarikan->handleStatus($attributes);

        return $penarikan;
    }

    public function update(array $attributes = [], array $options = [])
    {
        $this->validateData($attributes, 'u');
        $this->handleFile($attributes);
        $this->handleStatus($attributes);
        $result = parent::update($attributes, $options);

        return $result;
    }

    public function delete()
    {
        $result = parent::delete();
        $this->bukti()->delete();

        return $result;
    }
}