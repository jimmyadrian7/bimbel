<?php
namespace Bimbel\Pembayaran\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Siswa\Model\Siswa;
use Bimbel\Pembayaran\Model\TagihanDetail;
use Bimbel\Pembayaran\Model\Transaksi;
use Bimbel\Master\Model\Kursus;

class Tagihan extends BaseModel
{
    protected $fillable = [
        'code', 'siswa_id', 'sub_total', 'potongan', 'total', 'hutang', 'status', 'tanggal', 'kursus_id',
        'tagihan_detail'
    ];
    protected $table = 'tagihan';
    protected $appends = ['siswa_data'];

    protected $status_enum = [
        ["value" => "p", "label" => "Proses"],
        ["value" => "c", "label" => "Cicil"],
        ["value" => "l", "label" => "Lunas"]
    ];

    public function getSiswaDataAttribute() {
		$data = [
            "id" => $this->siswa->id,
            "nama" => $this->siswa->orang->nama
        ];

		return $data;
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

        $seq = new \Bimbel\Master\Model\Sequance();
        $attributes['code'] = $seq->getnextCode('pembiayaan');
        $this->autoFillData($attributes);
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

    public function delete()
    {
        // if ($this->status != 'p')
        // {
        //     throw new \Error("Tagihan cannot be deleted");
        // }

        $this->tagihan_detail()->delete();
        $this->transaksi()->delete();

        return parent::delete();
    }


    public function fetchAllData($condition, $obj, $pagination = false, $page = 1)
    {
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
        }

        return parent::fetchAllData($condition, $obj, $pagination, $page);
    }
    
    public function fetchDetail($id, $obj)
    {
        $obj = $obj->with('tagihan_detail', 'transaksi');
        $data = parent::fetchDetail($id, $obj);

        if ($data->status != 'p')
        {
            $data->editable = false;
            $data->deleteable = false;
        }

        return $data;
    }
}