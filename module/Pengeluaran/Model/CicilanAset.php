<?php

namespace Bimbel\Pengeluaran\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Master\Model\File;
use Bimbel\Pengeluaran\Model\TabunganAset;

class CicilanAset extends BaseModel 
{
    protected $fillable = ['tabungan_aset_id', 'bukti_pembayaran_id', 'bukti_pembayaran', 'pengeluaran_id', 'nominal'];
    protected $table = 'cicilan_aset';
    protected $with = ['bukti_pembayaran'];

    
    public function bukti_pembayaran()
    {
        return $this->hasOne(File::class, 'id', 'bukti_pembayaran_id');
    }
    public function tabungan_aset()
    {
        return $this->belongsTo(File::class, 'tabungan_aset_id', 'id');
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

    public function createPengeluaran($attributes, $tabungan_aset)
    {
        $nominal = $attributes['nominal'];        
        $pengeluaran = new \Bimbel\Pengeluaran\Model\Pengeluaran();
        $pengeluaran = $pengeluaran->create([
            "nama" => sprintf("Cicilan %s", $tabungan_aset->nama),
            "jumlah" => 1,
            "harga" => $nominal,
            "aset" => true,
            "kursus_id" => $tabungan_aset->kursus_id
        ]);

        return $pengeluaran;
    }

    public function autoFill(&$attributes)
    {
        if (!array_key_exists('nominal', $attributes) || !array_key_exists('tabungan_aset_id', $attributes))
        {
            throw new \Error("Please input nominal");
        }

        $tabungan_aset = new TabunganAset();
        $tabungan_aset = $tabungan_aset->find($attributes['tabungan_aset_id']);
        $sisa = $tabungan_aset->sisa - $attributes['nominal'];
        $status = 'c';

        if ($sisa < 0)
        {
            throw new \Error("Nominal cannot be greater then utang");
        }

        if ($sisa === 0)
        {
            $status = 'l';
        }

        $tabungan_aset->update([
            'sisa' => $sisa,
            'status' => $status
        ]);

        $attributes['pengeluaran_id'] = $this->createPengeluaran($attributes, $tabungan_aset)->id;
    }

    public function create(array $attributes = [])
    {
        self::handleFile($attributes);
        $this->autoFill($attributes);
        
        $cicilan_asset = parent::create($attributes);
        return $cicilan_asset;
    }
}