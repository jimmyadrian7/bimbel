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

    public function getKomisiNominal($pembiayaan, $tagihan)
    {
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

        return $komisi;
    }

    public function getKomisi($attributes, $komisi = false)
    {
        $pembiayaan = new Pembiayaan();
        $tagihan = new Tagihan();
        $tagihan = $tagihan->find($attributes['tagihan_id']);
        $pembiayaan = $pembiayaan->find($attributes['pembiayaan_id']);
        $old_komisi = $this->getKomisiNominal($pembiayaan, $tagihan);

        if (!$komisi)
        {
            $komisi = $old_komisi;
        }

        // if (!$old_komisi)
        // {
        //     $komisi = false;
        // }

        if ($komisi)
        {
            $result = $attributes['total'] * $komisi / 100;
        }
        else
        {
            $result = $pembiayaan->nominal;
        }

        $bulan_total = 0;

        if (!empty($this->bulan))
        {
            $bulan_total = $this->bulan;
        }

        if (array_key_exists('bulan', $attributes) && !empty($attributes['bulan']))
        {
            $bulan_total = $attributes['bulan'];
        }

        if ($bulan_total > 0)
        {
            $result = $result / $bulan_total;
        }

        return $result;
    }

    public function autoFill(&$attributes)
    {
        $attributes['sub_total'] = $attributes['qty'] * $attributes['nominal'];
        $attributes['potongan'] = $this->getPotongan($attributes);
        $attributes['total'] = $this->getTotal($attributes);
        $attributes['komisi'] = $this->getKomisi($attributes);

        if (!array_key_exists('diskon_id', $attributes) || !empty($attributes['diskon_id']))
        {
            $attributes['diskon_id'] = null;
        }
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

    public function updateKomisi($komisi)
    {
        $additional_data = [
            'tagihan_id' => $this->tagihan_id, 
            'pembiayaan_id' => $this->pembiayaan_id, 
            'total' => $this->total,
            'bulan' => $this->bulan
        ];

        $attributes = ['komisi' => $this->getKomisi($additional_data, $komisi)];

        return parent::update($attributes, []);
    }

    public function format()
    {
        $persen_komisi = 0;

        if ($this->pembiayaan->jenis_komisi == "s")
        {
            $persen_komisi = $this->tagihan->siswa->komisi;
        }

        if ($this->pembiayaan->jenis_komisi == "p")
        {
            $persen_komisi = $this->pembiayaan->nominal;
        }

        $jenis_pembayaran = $this->tagihan->transaksi->last()->jenis_pembayaran;

        return [
            "nama_siswa" => $this->tagihan->siswa->orang->nama,
            "kode_tagihan" => $this->tagihan->code,
            "tanggal_tagihan" => $this->tagihan->tanggal,
            "tanggal_lunas" => $this->tagihan->tanggal_lunas,
            "nama_item" => $this->nama,
            "harga_total" => $this->total,
            "persen_komisi" => $persen_komisi,
            "kursus" => $this->tagihan->kursus->nama,
            "jenis_transaksi" => $jenis_pembayaran,
            "komisi" => $this->komisi_akhir,
        ];
    }
}