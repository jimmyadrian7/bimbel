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
    

    /*
        @potongan => total potongan
        @sub_total => total sebelum potongan
        @komisi => total komisi yang diterima
    */
    public function hitungTagihan($guru_id, $year, $month)
    {
        $tagihans = new \Bimbel\Pembayaran\Model\Tagihan();

        $result = [
            "potongan" => 0,
            "sub_total" => 0,
            "komisi" => 0
        ];

        $tanggal = new \DateTime($year . "-" . $month . "-01");
        $tanggal->modify("-1month");
        $tanggal = $tanggal->format("Y-n");
        $tanggal = explode("-", $tanggal);
        $year = $tanggal[0];
        $month = $tanggal[1];

        $tds1 = $this->getTagihanDetailDll($guru_id, $year, $month);
        $result["potongan"] += $tds1->sum("potongan");
        $result["sub_total"] += $tds1->sum("sub_total");
        $result["komisi"] += $tds1->sum("komisi");

        $tds1 = $this->getTagihanDetailIuran($guru_id, $year, $month);
        $result["potongan"] += $tds1->sum("potongan");
        $result["sub_total"] += $tds1->sum("sub_total");
        $result["komisi"] += $tds1->sum("komisi");

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

        $tanggal = explode("-", $attributes['tanggal']);
        $year = $tanggal[0];
        $month = $tanggal[1];

        $komisi = $this->hitungTagihan($guru->id, $year, $month);
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

    public function update(array $attributes = [], array $options = [])
    {
        $this->validateData($attributes);
        $this->autoFillData($attributes);
        return parent::update($attributes, $options);
    }


    public function getTagihanDetailDll($guru_id, $year, $month)
    {
        $tagihan_detail = new \Bimbel\Pembayaran\Model\TagihanDetail();
        $tagihan_detail = $tagihan_detail
            ->where('komisi', '>', 0)
            ->whereHas("tagihan", function($q) use ($guru_id, $year, $month)
            {
                $q->whereMonth('tanggal_lunas', '<=', $month)
                    ->whereYear('tanggal_lunas', '>=', $year)
                    ->whereHas('siswa', function($query) use ($guru_id) {
                        $query->where('guru_id', $guru_id);
                    })
                    ->where('status', 'l');
            })
            ->where('system', false)
            ->get();

        return $tagihan_detail;
    }

    /*
        case untuk iuran:
        1. lunas sebelum bulannya
        2. lunas tepat pada bulannya
        3. lunas telat
    */
    public function getTagihanDetailIuran($guru_id, $year, $month)
    {
        $tagihan_detail = new \Bimbel\Pembayaran\Model\TagihanDetail();

        $tanggal_gaji = $year . "-" . $month . "-1";
        $tanggal_gaji = new \DateTime($tanggal_gaji);
        $tanggal_gaji = $tanggal_gaji->format("Y-m-d");

        $tagihan_detail = $tagihan_detail
            ->where('komisi', '>', 0)
            ->where('system', true)
            ->whereDate("tanggal_iuran_mulai", ">=", $tanggal_gaji)
            ->whereDate("tanggal_iuran_berakhir", "<=", $tanggal_gaji)
            ->whereHas('tagihan', function($q) {
                $q->where('status', 'l');
            })
            ->get();

        foreach ($tagihan_detail as $td)
        {
            $tanggal_lunas = $td->tagihan->tanggal_lunas;
            $tanggal_lunas = new \DateTime($tanggal_lunas);
            $tanggal_gajian = new \DateTime($tanggal_gaji);
            
            $interval = $tanggal_lunas->diff($tanggal_gajian);
            $interval = $interval->m + 12 * $interval->y;

            if ($interval > 0)
            {
                $td->komisi = $td->komisi * $interval;
            }
        }

        return $tagihan_detail;
    }

    public function getTunjangan($guru_id)
    {
        $tunjangan = new \Bimbel\Guru\Model\TunjanganGuru();
        $tunjangan = $tunjangan->where('guru_id', $guru_id)->get();

        return $tunjangan;
    }
}