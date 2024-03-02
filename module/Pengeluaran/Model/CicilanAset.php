<?php

namespace Bimbel\Pengeluaran\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Master\Model\File;
use Bimbel\Pengeluaran\Model\TabunganAset;

class CicilanAset extends BaseModel 
{
    protected $fillable = ['tabungan_aset_id', 'bukti_pembayaran_id', 'bukti_pembayaran', 'pengeluaran_id', 'nominal', 'tanggal', 'status'];
    protected $table = 'cicilan_aset';
    protected $with = ['bukti_pembayaran'];

    protected $status_enum = [
        ["value" => "m", "label" => "Menunggu Verifikasi"],
        ["value" => "s", "label" => "Sukses"]
    ];

    
    public function bukti_pembayaran()
    {
        return $this->hasOne(File::class, 'id', 'bukti_pembayaran_id');
    }
    public function tabungan_aset()
    {
        return $this->belongsTo(TabunganAset::class, 'tabungan_aset_id', 'id');
    }


    public function handleFile(&$attributes)
    {
        $file = new File();
        $isCreate = true;
        $bukti_pembayaran = self::getValue($attributes, 'bukti_pembayaran');

        if (empty($bukti_pembayaran))
        {
            return;
        }

        if (!empty($this->bukti_pembayaran_id))
        {
            $file = $this->bukti_pembayaran;
            $isCreate = false;
        }

        if ($isCreate)
        {
            $file = $file->create($bukti_pembayaran);
        }
        else
        {
            $file->update($bukti_pembayaran);
        }

        $attributes['bukti_pembayaran_id'] = $file->id;
    }

    public function createPengeluaran($tabungan_aset)
    {
        $pengeluaran = new Pengeluaran();
        $pengeluaran_value = [
            "nama" => sprintf("Cicilan %s", $tabungan_aset->nama),
            "jumlah" => 1,
            "harga" => $this->nominal,
            "aset" => true,
            "kursus_id" => $tabungan_aset->kursus_id,
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
        if (!array_key_exists('tabungan_aset_id', $attributes))
        {
            if (!$this->tabungan_aset_id)
            {
                throw new \Error("Data tidak valid");
            }

            $attributes['tabungan_aset_id'] = $this->tabungan_aset_id;
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

        $tabungan_aset = TabunganAset::findOrFail($attributes['tabungan_aset_id']);

        if ($tabungan_aset->sisa < $attributes['nominal'])
        {
            throw new \Error("Nominal tidak dapat lebih besar dari Sisa Utang");
        }
    }

    public function handleStatus(&$attributes)
    {
        if (array_key_exists('status', $attributes) && $attributes['status'] == 's')
        {
            $sisa = $this->nominal - $this->tabungan_aset->sisa;
            $status = 'l';

            if ($sisa > 0)
            {
                $status = 'c';
            }

            $this->tabungan_aset->update(['sisa' => $sisa, 'status' => $status]);
            
            $pengeluaran_id = $this->createPengeluaran($this->tabungan_aset)->id;
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
        $this->bukti_pembayaran()->delete();

        return $result;
    }
}