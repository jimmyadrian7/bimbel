<?php
namespace Bimbel\Pembayaran\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Pembayaran\Model\Tagihan;
use Bimbel\Master\Model\File;


class Transaksi extends BaseModel
{
    protected $fillable = ['tagihan_id', 'nominal', 'bukti_pembayaran_id', 'bukti_pembayaran', 'tanggal', 'status'];
    protected $table = 'transaksi';
    protected $with = ['bukti_pembayaran'];

    protected $status_enum = [
        ["value" => "p", "label" => "Proses"],
        ["value" => "v", "label" => "Verif"]
    ];


    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class, 'tagihan_id', 'id');
    }
    public function bukti_pembayaran()
    {
        return $this->hasOne(File::class, 'id', 'bukti_pembayaran_id');
    }


    public function updateTagihan()
    {        
        $tagihan_value = [
            'hutang' => $this->tagihan->hutang - $this->nominal,
            'status' => 'c',
            'siswa_id' => $this->tagihan->siswa_id
        ];

        if ($tagihan_value['hutang'] === 0)
        {
            $tagihan_value['status'] = 'l';
            $tagihan_value['tanggal_lunas'] = $this->tanggal;
        }

        $this->tagihan->update($tagihan_value);
    }

    public function handleTagihanDetail()
    {
        $transaksi = $this;
        
        if ($transaksi->tagihan->status != 'l')
        {
            return;
        }

        foreach ($this->tagihan->tagihan_detail as $tagihan_detail)
        {
            switch ($tagihan_detail->kategori_pembiayaan) {
                case 'd':
                    $deposit = new \Bimbel\Siswa\Model\Deposit();
                    $eposit = $deposit->create([
                        'nominal' => $tagihan_detail->total,
                        'siswa_id' => $transaksi->tagihan->siswa_id
                    ]);
                break;

                case 'p':
                    $transaksi->tagihan->siswa->update(['status' => 'a']);
                break;
            }

            if ($tagihan_detail->pembiayaan->stok)
            {
                $tagihan_detail->pembiayaan->update([
                    'jumlah_stok' => $tagihan_detail->pembiayaan->jumlah_stok - $tagihan_detail->qty
                ]);
            }
        }
    }


    public function validateData($attributes)
    {
        if (!array_key_exists('tagihan_id', $attributes) || !array_key_exists('nominal', $attributes))
        {
            throw new \Error("Data not valid");
        }

        $tagihan_id = $attributes['tagihan_id'];
        $nominal = $attributes['nominal'];

        $tagihan = new Tagihan();
        $tagihan = $tagihan->find($tagihan_id);

        if (!$tagihan)
        {
            throw new \Error("Tagihan not found");
        }
        
        $sisa_hutang = $tagihan->hutang - $nominal;
        if ($sisa_hutang < 0)
        {
            throw new \Error("Pembayaran tidak dapat lebih besar dari hutang");
        }
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

    public function handleStatus()
    {
        if ($this->status != 'v')
        {
            return;
        }

        $this->updateTagihan();
        $this->handleTagihanDetail();
    }
    
    public function cekStatus($attributes)
    {
        if (!array_key_exists('status', $attributes) || $attributes['status'] != 'v')
        {
            return;
        }

        if ($this->tagihan->hutang < $this->nominal)
        {
            throw new \Error("Pembayaran tidak dapat lebih besar dari hutang");
        }
    }


    public function create(array $attributes = [])
    {
        $this->validateData($attributes);
        $this->handleFile($attributes);

		$transaksi = parent::create($attributes);
        $transaksi->handleStatus();

        return $transaksi;
    }

    public function update(array $attributes = [], array $options = [])
    {
        $this->handleFile($attributes);
        $this->cekStatus($attributes);

        if (array_key_exists('tagihan_id', $attributes) && array_key_exists('nominal', $attributes))
        {
            $this->validateData($attributes);
        }


        $result = parent::update($attributes, $options);

        $this->refresh();

        if (array_key_exists('status', $attributes))
        {
            $this->handleStatus();
        }

        return $result;
    }
}