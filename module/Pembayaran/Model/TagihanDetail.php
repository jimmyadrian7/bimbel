<?php
namespace Bimbel\Pembayaran\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Pembayaran\Model\Tagihan;
use Bimbel\Pembayaran\Model\Pembiayaan;
use Bimbel\Pembayaran\Model\Diskon;

class TagihanDetail extends BaseModel
{
    protected $fillable = [
        'tagihan_id', 'kode', 'nama', 'nominal', 'sub_total', 'qty', 'komisi_guru', 'total',
        'kategori_pembiayaan', 'potongan', 'total', 'pembiayaan_id', 'diskon_id', 'komisi',
        'tanggal_iuran_mulai', 'tanggal_iuran_berakhir', 'system', 'bulan'
    ];
    protected $table = 'tagihan_detail';
    protected $with = ['diskon', 'pembiayaan'];


    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class, 'tagihan_id', 'id');
    }
    public function pembiayaan()
    {
        return $this->hasOne(Pembiayaan::class, 'id', 'pembiayaan_id');
    }
    public function diskon()
    {
        return $this->hasOne(Diskon::class, 'id', 'diskon_id');
    }
    
    
    public function validateData(&$attributes)
    {
        $isValid = true;
        $required_fields = ['nominal', 'qty', 'pembiayaan_id', 'tagihan_id'];

        foreach ($required_fields as $required_field)
        {
            if (!array_key_exists($required_field, $attributes))
            {
                $isValid = false;
                break;
            }
        }

        if(!$isValid)
        {
            throw new \Error("Data not valid");
        }
    }

    public function getPotongan($attributes)
    {
        $potongan = 0;
        $sub_total = $attributes['sub_total'];

        if (array_key_exists('diskon_id', $attributes) && !empty($attributes['diskon_id']))
        {
            $diskon = new Diskon();
            $diskon = $diskon->find($attributes['diskon_id']);
            $nominal_potong = $diskon->diskon;

            if ($diskon->tipe_diskon === 'p')
            {
                $nominal_potong = $sub_total * $nominal_potong / 100;
            }

            $potongan = $nominal_potong;
        }

        return $potongan;
    }

    public function getTotal($attributes)
    {
        $result = $attributes['sub_total'] - $attributes['potongan'];
        return $result;
    }

    public function getKomisi($attributes)
    {
        $pembiayaan = new Pembiayaan();
        $tagihan = new Tagihan();
        $tagihan = $tagihan->find($attributes['tagihan_id']);
        $pembiayaan = $pembiayaan->find($attributes['pembiayaan_id']);
        $komisi = false;

        if ($pembiayaan->jenis_komisi == 's')
        {
            $komisi = $tagihan->siswa->komisi;
        }
        else if ($pembiayaan->jenis_komisi == 'p')
        {
            $komisi = $pembiayaan->nominal;
        }
        else
        {
            $komisi = false;
        }

        if ($komisi !== false)
        {
            $result = $attributes['total'] * $komisi / 100;
        }
        else
        {
            $result = $pembiayaan->nominal * $attributes['qty'];
        }

        return $result;
    }

    public function autoFill(&$attributes)
    {
        $attributes['sub_total'] = $attributes['qty'] * $attributes['nominal'];
        $attributes['potongan'] = $this->getPotongan($attributes);
        $attributes['total'] = $this->getTotal($attributes);
        $attributes['komisi'] = $this->getKomisi($attributes);
    }

    public function create(array $attributes = [])
    {
        self::validateData($attributes);
        self::autoFill($attributes);
        return parent::create($attributes);
    }

    public function update(array $attributes = [], array $options = [])
    {
        $this->validateData($attributes);
        $this->autoFill($attributes);
        return parent::update($attributes, $options);
    }
}