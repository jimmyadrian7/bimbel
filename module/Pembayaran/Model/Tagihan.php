<?php
namespace Bimbel\Pembayaran\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Siswa\Model\Siswa;
use Bimbel\Guru\Model\Guru;
use Bimbel\Pembayaran\Model\TagihanDetail;
use Bimbel\Pembayaran\Model\Transaksi;
use Bimbel\Master\Model\Kursus;
use Bimbel\Siswa\Model\IuranTerbuat;

class Tagihan extends BaseModel
{
    protected $fillable = [
        'code', 'siswa_id', 'sub_total', 'potongan', 'total', 'hutang', 'status', 'tanggal', 'kursus_id',
        'tagihan_detail', 'tanggal_lunas', 'guru_id', 'program_belajar', 'jenis_pembayaran', 'terima_dari',
        'untuk_pembayaran', 'keterangan'
    ];
    protected $table = 'tagihan';
    protected $appends = ['siswa_data', 'verify_transaksi'];

    public $required_field = [
        ['name' => 'siswa_id', 'label' => 'Siswa'],
        ['name' => 'tagihan_detail', 'label' => 'Tagihan Detail']
    ];

    protected $status_enum = [
        ["value" => "p", "label" => "Proses"],
        ["value" => "c", "label" => "Menunggu Verifikasi"],
        ["value" => "l", "label" => "Lunas"]
    ];

    public function getSiswaDataAttribute() {
		$data = [
            "id" => $this->siswa->id,
            "nama" => $this->siswa->orang->nama,
            "komisi" => $this->siswa->komisi
        ];

		return $data;
	}

    public function getVerifyTransaksiAttribute() {
        $result = $this->transaksi()->where('status', 'p')->count();

        return $result;
    }

    
    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'id', 'siswa_id');
    }
    public function tagihan_detail()
    {
        return $this->hasMany(TagihanDetail::class, 'tagihan_id', 'id');
    }
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'tagihan_id', 'id');
    }
    public function kursus()
    {
        return $this->hasOne(Kursus::class, 'id', 'kursus_id');
    }
    public function guru()
    {
        return $this->hasOne(Guru::class, 'id', 'guru_id');
    }


    public function handleTagihanDetail($tagihan_details)
    {
        $tagihan_id = $this->id;
        $tagihan_detail_ids = [];
        $sub_total = 0;
        $potongan = 0;
        $total = 0;

        if (empty($tagihan_details))
        {
            return;
        }

        foreach($tagihan_details as $tagihan_detail_value)
        {
            $tagihan_detail = new TagihanDetail();
            $isCreate = false;

            $tagihan_detail_value['tagihan_id'] = $tagihan_id;
            
            if (array_key_exists('id', $tagihan_detail_value))
            {
                $tagihan_detail = $tagihan_detail->find($tagihan_detail_value['id']);
                if(!$tagihan_detail)
                {
                    $isCreate = true;
                }
                unset($tagihan_detail_value['id']);
            }
            else 
            {
                $tagihan_detail = $tagihan_detail
                    ->where('kode', $tagihan_detail_value['kode'])
                    ->where('nama', $tagihan_detail_value['nama'])
                    ->where('tagihan_id', $tagihan_id)
                    ->first();
                
                if (!$tagihan_detail)
                {
                    $isCreate = true;
                }
            }

            if ($isCreate)
            {
                $tagihan_detail = new TagihanDetail();
                $tagihan_detail = $tagihan_detail->create($tagihan_detail_value);
            }
            else
            {
                $tagihan_detail->update($tagihan_detail_value);
            }

            $sub_total += $tagihan_detail->sub_total;
            $potongan += $tagihan_detail->potongan;
            $total += $tagihan_detail->total;
            array_push($tagihan_detail_ids, $tagihan_detail->id);
        }
        
        $tagihan_detail = new TagihanDetail();
        $tagihan_detail
            ->where('tagihan_id', $tagihan_id)
            ->whereNotIn('id', $tagihan_detail_ids)
            ->delete();

        $tagihan_value = [
            'siswa_id' => $this->siswa_id,
            'potongan' => $potongan,
            'sub_total' => $sub_total,
            'total' => $total,
            'hutang' => $total
        ];
        $this->update($tagihan_value);
    }

    public function autoFillData(&$attributes)
    {
        if (!array_key_exists('siswa_id', $attributes))
        {
            return;
        }

        $siswa = new Siswa();
        $siswa = $siswa->find($attributes['siswa_id']);
        $attributes['kursus_id'] = $siswa->kursus_id;
        $attributes['guru_id'] = $siswa->guru_id;

        if (!array_key_exists('status', $attributes) || empty($attributes['status']))
        {
            $attributes['status'] = 'p';
        }
    }

    public function validateData($attributes)
    {
        if (!array_key_exists('siswa_id', $attributes) || empty($attributes['siswa_id']))
        {
            throw new \Error("Please input siswa");
        }
    }

    public function create(array $attributes = [])
    {
        $this->validateData($attributes);

        $tagihan_details = self::getValue($attributes, 'tagihan_detail');
        $this->autoFillData($attributes);

        $seq = new \Bimbel\Master\Model\Kursus();
        $attributes['code'] = $seq->getnextCode($attributes['kursus_id']);
        
		$tagihan = parent::create($attributes);
        $tagihan->handleTagihanDetail($tagihan_details);

        return $tagihan;
    }

    public function update(array $attributes = [], array $options = [])
    {
        $this->validateData($attributes);
        
        $tagihan_details = self::getValue($attributes, 'tagihan_detail');
        $this->autoFillData($attributes);
        $result = parent::update($attributes, $options);

        $this->handleTagihanDetail($tagihan_details);

        return $result;
    }

    public function updateGuru($guru_id, $komisi)
    {
        parent::update(['guru_id' => $guru_id], []);
        foreach($this->tagihan_detail as $td)
        {
            $td->updateKomisi($komisi);
        }
    }

    public function delete()
    {
        // if ($this->status != 'p')
        // {
        //     throw new \Error("Tagihan cannot be deleted");
        // }

        // update iuran siswa
        $tagihan_details = $this->tagihan_detail()->where('system', 1)->where('kategori_pembiayaan', 's')->get();

        foreach ($tagihan_details as $tagihan_detail) {
            $iuran_terbuat = new IuranTerbuat();
            $siswa_id = $tagihan_detail->tagihan->siswa_id;
            $iuran = $tagihan_detail->tagihan->siswa->iuran()
                ->whereHas('iuran_detail', function($q) use ($tagihan_detail) {
                    $q->where('pembiayaan_id', $tagihan_detail->pembiayaan_id);
                })
                ->where('bulan', $tagihan_detail->bulan)
                ->first()
            ;

            $iuran_terbuat = $iuran_terbuat->where('siswa_id', $siswa_id)->where('iuran_id', $iuran->id)->first();

            if ($iuran_terbuat)
            {
                $tanggal_akhir = $tagihan_detail->tanggal_iuran_berakhir;
                $tanggal_iuran = $iuran_terbuat->tahun . "-" . $iuran_terbuat->bulan . "-01";
                $tanggal_iuran = strtotime($tanggal_iuran);
                $tanggal_iuran = date('Y-m-d', $tanggal_iuran);

                if ($tanggal_akhir == $tanggal_iuran)
                {
                    $prev_date = new \DateTime($tagihan_detail->tanggal_iuran_mulai);
                    $prev_date->modify("- 1 month");
                    
                    $iuran_terbuat->tahun = $prev_date->format("Y");
                    $iuran_terbuat->bulan = $prev_date->format("n");
                    $iuran_terbuat->save();
                }
            }

            if ($this->status == 'l')
            {
                if ($tagihan_detail->pembiayaan->stok)
                {
                    $tagihan_detail->pembiayaan->update([
                        'jumlah_stok' => $tagihan_detail->pembiayaan->jumlah_stok + $tagihan_detail->qty
                    ]);
                }
            }
        }

        $this->tagihan_detail()->delete();
        $this->transaksi()->delete();

        return parent::delete();
    }


    public function fetchAllData($condition, $obj, $pagination = false, $page = 1, $sort = [])
    {
        $obj = $obj->with('guru', 'guru.orang');

        foreach($condition as $key => $con)
        {
            if ($con[0] == 'nama')
            {
                $obj = $obj->whereHas('siswa', function($query) use ($con) {
                    $query->whereHas('orang', function($q) use ($con) {
                        $q->where([$con]);
                    });
                });
                unset($condition[$key]);
            }

            if ($con[0] == 'guru.orang.nama')
            {
                $obj = $obj->whereHas('guru', function($query) use ($con) {
                    $query->whereHas('orang', function($q) use ($con) {
                        $q->where("nama", $con[1], $con[2]);
                    });
                });
                unset($condition[$key]);
            }
        }

        return parent::fetchAllData($condition, $obj, $pagination, $page, $sort);
    }
    
    public function fetchDetail($id, $obj)
    {
        $obj = $obj->with('tagihan_detail', 'transaksi', 'guru', 'guru.orang');
        $data = parent::fetchDetail($id, $obj);

        if ($data->status != 'p')
        {
            $data->editable = false;
            // $data->deleteable = false;
        }

        if ($data->status == 'l')
        {
            // $data->editable = false;
            $session = new \Bimbel\Master\Model\Session();
            if ($session->isSuperUser())
            {
                $data->deleteable = true;
            }
            else
            {
                $data->deleteable = false;
            }
        }

        return $data;
    }
}