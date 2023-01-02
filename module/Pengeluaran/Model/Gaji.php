<?php
namespace Bimbel\Pengeluaran\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Guru\Model\Guru;
use Bimbel\Pengeluaran\Model\Pengeluaran;

class Gaji extends BaseModel
{
    protected $fillable = [
        'guru_id', 'total_siswa', 
        'potongan', 'sub_total', 'tunjangan', 'komisi', 'total',
        'tanggal', 'pengeluaran_id'
    ];
    protected $table = 'gaji';


    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id', 'id');
    }
    public function pengeluaran()
    {
        return $this->hasOne(Pengeluaran::class, 'pengeluaran_id', 'id');
    }
    

    public function getPotongan($guru, &$attributes)
    {
        $tagihan = new \Bimbel\Pembayaran\Model\Tagihan();
        $siswa_ids = $guru->siswa->pluck('id');
        $year = date("Y");
        $month = date("m");
        $potongan = 0;
        $sub_total = 0;
        $total = 0;
        $total_komisi = 0;

        $tagihans = $tagihan
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->whereIn('siswa_id', $siswa_ids)->get();

        foreach ($tagihans as $tagihan)
        {
            foreach ($tagihan->tagihan_detail as $tagihan_detail)
            {
                if ($tagihan_detail->komisi_guru === 0)
                {
                    continue;
                }

                $komisi = $tagihan_detail->total * $tagihan_detail->komisi_guru / 100;
                
                $sub_total += $tagihan_detail->sub_total;
                $potongan += $tagihan_detail->potongan;
                $total += $tagihan_detail->total;
                $total_komisi += $komisi;
            }
        }

        $attributes['sub_total'] = $sub_total;
        $attributes['potongan'] = $potongan;
        $attributes['total'] = $total;
        $attributes['komisi'] = $total_komisi;
    }

    /*
        @potongan => total potongan
        @sub_total => total sebelum potongan
        @komisi => total komisi yang diterima
    */
    public function hitungTagihan($siswa_ids)
    {
        $tagihans = new \Bimbel\Pembayaran\Model\Tagihan();
        $year = date("Y");
        $month = date("m");

        $result = [
            "potongan" => 0,
            "sub_total" => 0,
            "komisi" => 0
        ];

        $tagihans = $tagihans
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->whereIn('siswa_id', $siswa_ids)->get();

        foreach ($tagihans as $tagihan)
        {
            foreach ($tagihan->tagihan_detail as $tagihan_detail)
            {
                if ($tagihan_detail->komisi_guru === 0)
                {
                    continue;
                }
                
                $result['potongan'] += $tagihan_detail->potongan;
                $result['sub_total'] += $tagihan_detail->sub_total;
                $result['komisi'] += $tagihan_detail->komisi;
            }
        }

        return $result;
    }

    public function hitungTunjangan($guru)
    {
        $result = $guru->tunjangan_guru->sum('nominal');

        return $result;
    }

    public function autoFillData(&$attributes)
    {
        $guru = new Guru();
        $guru = $guru->find($attributes['guru_id']);
        $siswa_ids = $guru->siswa->pluck('id');

        $komisi = $this->hitungTagihan($siswa_ids);
        $tunjangan = $this->hitungTunjangan($guru);

        $attributes['total_siswa'] = $guru->siswa->count();
        $attributes['potongan'] = $komisi['potongan'];
        $attributes['sub_total'] = $komisi['sub_total'];
        $attributes['komisi'] = $komisi['komisi'];
        $attributes['tunjangan'] = $tunjangan;
        $attributes['total'] = $komisi['komisi'] + $tunjangan;
    }


    public function validateData($attributes)
    {
        if(!array_key_exists('guru_id', $attributes))
        {
            throw new \Error("Guru not found");
        }
    }

    public function create(array $attributes = [])
    {
        $this->validateData($attributes);
        $this->autoFillData($attributes);
        
		$gaji = parent::create($attributes);
        return $gaji;
    }
}