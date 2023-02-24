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
    public function hitungTagihan($siswa_ids, $year, $month)
    {
        /*
            case untuk iuran:
            1. lunas sebelum bulannya
            2. lunas tepat pada bulannya
            3. lunas telat
        */

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

        $tagihans = $tagihans
            ->whereYear('tanggal_lunas', $year)
            ->whereMonth('tanggal_lunas', $month)
            ->whereIn('siswa_id', $siswa_ids)
            ->where('status', 'l')
            ->get();

        foreach ($tagihans as $tagihan)
        {
            foreach ($tagihan->tagihan_detail as $tagihan_detail)
            {
                if ($tagihan_detail->komisi === 0)
                {
                    continue;
                }

                if (!$tagihan_detail->system)
                {
                    // komisi selain iuran
                    $result['potongan'] += $tagihan_detail->potongan;
                    $result['sub_total'] += $tagihan_detail->sub_total;
                    $result['komisi'] += $tagihan_detail->komisi;
                }
                else
                {
                    // case ke 2 (lunas tepat pada bulannya)
                    $tanggal_lunas = $tagihan->tanggal_lunas;
                    $tanggal_lunas = explode("-", $tanggal_lunas);
                    $tanggal_lunas = (int) ($tanggal_lunas[0] . $tanggal_lunas[1] . "01");

                    $start_iuran = $tagihan_detail->tanggal_iuran_mulai;
                    $start_iuran = (int) str_replace("-", "", $start_iuran);

                    if ($tanggal_lunas == $start_iuran)
                    {
                        $result['potongan'] += $tagihan_detail->potongan;
                        $result['sub_total'] += $tagihan_detail->sub_total;
                        $result['komisi'] += ($tagihan_detail->komisi / $tagihan_detail->bulan);
                    }
                }
            }
        }

        $tanggal_gaji = $year . "-" . $month . "-1";
        $tanggal_gaji = new \DateTime($tanggal_gaji);
        $tanggal_gaji = $tanggal_gaji->format("Y-m-d");

        $tagihan_details = new \Bimbel\Pembayaran\Model\TagihanDetail();
        $tagihan_details = $tagihan_details
            ->whereDate("tanggal_iuran_mulai", "<=", $tanggal_gaji)
            ->whereDate("tanggal_iuran_berakhir", ">=", $tanggal_gaji)
            ->whereHas('tagihan', function($q) {
                $q->where('status', 'l');
            })
            ->get();

        foreach($tagihan_details as $tagihan_detail)
        {
            $tanggal_lunas = $tagihan_detail->tagihan->tanggal_lunas;
            $int_tanggal_lunas = explode("-", $tanggal_lunas);
            $int_tanggal_lunas = (int) ($int_tanggal_lunas[0] . $int_tanggal_lunas[1] . "01");

            $int_tanggal_gaji = (int) str_replace("-", "", $tanggal_gaji);

            // case 1 (lunas sebelum bulannya)
            if ($int_tanggal_lunas < $int_tanggal_gaji)
            {
                $result['potongan'] += $tagihan_detail->potongan;
                $result['sub_total'] += $tagihan_detail->sub_total;
                $result['komisi'] += ($tagihan_detail->komisi / $tagihan_detail->bulan);
            }
            else
            {
                // case 3 (lunas telat)
                $tanggal_lunas = new \DateTime($tanggal_lunas);
                $tanggal_gajian = new \DateTime($tanggal_gaji);
                
                $interval = $tanggal_lunas->diff($tanggal_gajian);
                $interval = $interval->m + 12 * $interval->y;

                $result['potongan'] += $tagihan_detail->potongan;
                $result['sub_total'] += $tagihan_detail->sub_total;
                $result['komisi'] += (($tagihan_detail->komisi / $tagihan_detail->bulan) * $interval);
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

        $tanggal = explode("-", $attributes['tanggal']);
        $year = $tanggal[0];
        $month = $tanggal[1];

        $komisi = $this->hitungTagihan($siswa_ids, $year, $month);
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
}