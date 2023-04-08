<?php
namespace Bimbel\Siswa\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Siswa\Model\Siswa;
use Bimbel\Pembayaran\Model\Iuran;

class IuranTerbuat extends BaseModel
{
    protected $fillable = ['siswa_id', 'bulan', 'tahun', 'iuran_id'];
    protected $table = 'iuran_terbuat';


    public function iuran()
    {
        return $this->hasOne(Iuran::class, 'id', 'iuran_id');
    }
    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'id', 'siswa_id');
    }

    
    public function getTagihanDetail($firstTime = false, $tanggal = false)
    {
        $iuran_terbuat = $this;
        $result = [];

        if ($tanggal)
        {
            $latestDate = explode("-", $tanggal);
        }else
        {
            $latestDate = new \DateTime('now');
            // $latestDate->modify('+'. ($this->iuran->bulan-1) .' month');
            $latestDate = $latestDate->format('Y-n');
            $latestDate = explode("-", $latestDate);
        }
        
        $bulan = $latestDate[1];
        $tahun = $latestDate[0];
        $total_tanggal = (int) ($tahun .  $bulan);
        $total_current_tanggal = (int) ($this->tahun . sprintf('%02d', $this->bulan));

        $tanggal_start = $tahun . "-" . $bulan . "-1";
        $tanggal_end = new \DateTime($tanggal_start);
        $tanggal_end->modify("+" . ($this->iuran->bulan - 1) . "month");
        $tanggal_end = $tanggal_end->format("Y-n-1");

        if ($this->bulan != null && $this->tahun != null && $total_tanggal <= $total_current_tanggal)
        {
            return $result;
        }

        foreach ($iuran_terbuat->iuran->iuran_detail as $iuran_detail) {
            if (!$firstTime && $iuran_detail->skip)
            {
                continue;
            }

            $tagihan_detail = [
                "kategori_pembiayaan" => $iuran_detail->pembiayaan->kategori_pembiayaan,
                "kode" => $iuran_detail->pembiayaan->kode,
                "nama" => $iuran_detail->pembiayaan->nama,
                "nominal" => $iuran_detail->pembiayaan->harga,
                "qty" => $iuran_detail->qty,
                'pembiayaan_id' => $iuran_detail->pembiayaan->id,
                'system' => true,
                'tanggal_iuran_mulai' => $tanggal_start,
                'tanggal_iuran_berakhir' => $tanggal_end,
                'bulan' => $iuran_terbuat->iuran->bulan
            ];
            array_push($result, $tagihan_detail);
        }

        return $result;
    }
    public function validateDate()
    {
        $result = true;
        $iuran_date = 0;
        $current_date = date("Yn");

        if (!empty($this->month) && !empty($this->year))
        {
            $iuran_date = $this->year . $this->month;
        }

        if ($current_date <= $iuran_date)
        {
            $result = false;
        }

        return $result;
    }
    public function createTagihan($iuran_id, $siswa, $firstTime = false)
    {
        if (!$this->validateDate())
        {
            throw new \Error("Tagihan already created");
        }

        $tagihan = new \Bimbel\Pembayaran\Model\Tagihan();
        $tagihan_detail = $this->getTagihanDetail($firstTime);

        if (count($tagihan_detail) === 0)
        {
            throw new \Error("Tagihan is empty");
        }

        $tagihan = $tagihan->create([
            "siswa_id" => $this->siswa_id,
            "tagihan_detail" => $tagihan_detail
        ]);

        return $tagihan;
    }
    public function updateDate($tanggal = false)
    {
        if (!$tanggal)
        {
            $latestDate = new \DateTime('now');
        }
        else
        {
            $latestDate = explode("-", $tanggal);
            $bulan = $latestDate[1];
            $tahun = $latestDate[0];

            $cekbulan = $bulan - $this->bulan;
            $cektahun = $tahun - $this->tahun;

            if ($cektahun < 0 && $cekbulan < 0)
            {
                throw new \Error("Tanggal telah tergenerate");
            }

            $latestDate = new \DateTime($tanggal);
        }

        $latestDate->modify('+'. ($this->iuran->bulan-1) .' month');
        $latestDate = $latestDate->format('Y-n');
        $latestDate = explode("-", $latestDate);

        $this->update([
            'bulan' => $latestDate[1],
            'tahun' => $latestDate[0]
        ]);
    }

    public function create(array $attributes = [])
    {
        $iuran_id = self::getValue($attributes, 'iuran_id', false);
        $siswa_id = self::getValue($attributes, 'siswa_id', false);

        if (empty($iuran_id) || empty($siswa_id))
        {
            throw new \Error("Please define iuran and siswa");
        }

        $iuran_terbuat = parent::create($attributes);
        return $iuran_terbuat;
    }
}
