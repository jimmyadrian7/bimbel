<?php

namespace Bimbel\Pengeluaran\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Master\Model\File;
use Bimbel\Pengeluaran\Model\Modal;

class CicilanModal extends BaseModel 
{
    protected $fillable = ['modal_id', 'bukti_id', 'bukti', 'pengeluaran_id', 'nominal', 'tanggal', 'status'];
    protected $table = 'cicilan_modal';

    protected $status_enum = [
        ["value" => "m", "label" => "Menunggu Verifikasi"],
        ["value" => "s", "label" => "Sukses"]
    ];

    
    public function bukti()
    {
        return $this->hasOne(File::class, 'id', 'bukti_id');
    }
    public function modal()
    {
        return $this->belongsTo(Modal::class, 'modal_id', 'id');
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

    public function createPengeluaran($modal)
    {
        $pengeluaran = new Pengeluaran();
        $pengeluaran_value = [
            "nama" => sprintf("Cicilan Modal %s", $modal->kursus->nama),
            "jumlah" => 1,
            "harga" => $this->nominal,
            "aset" => true,
            "kursus_id" => $modal->kursus_id,
            "tanggal" => $this->tanggal
        ];

        if (!empty($this->pengeluaran_id))
        {
            $pengeluaran = $this->pengeluaran;
            $this->pengeluaran->update($pengeluaran_value);
        }
        else
        {
            $pengeluaran = $pengeluaran->create($pengeluaran_value);
        }

        return $pengeluaran;
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
                throw new \Error("Harap isi nominal");
            }

            $attributes['nominal'] = $this->nominal;
        }

        if (!array_key_exists('tanggal', $attributes))
        {
            if (!$this->tanggal)
            {
                throw new \Error("Harap isi tanggal");
            }
        }

        $modal = Modal::findOrFail($attributes['modal_id']);

        if ($modal->sisa < $attributes['nominal'])
        {
            throw new \Error("Nominal tidak dapat lebih besar dari Sisa Utang");
        }
    }

    public function handleStatus(&$attributes)
    {
        if (array_key_exists('status', $attributes) && $attributes['status'] == 's')
        {
            $sisa = $this->modal->sisa - $this->nominal;
            $status = 'l';

            if ($sisa > 0)
            {
                $status = 'c';
            }

            $this->modal->update(['sisa' => $sisa, 'status' => $status]);
            
            $pengeluaran_id = $this->createPengeluaran($this->modal)->id;
            parent::update(['pengeluaran_id' => $pengeluaran_id], []);
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