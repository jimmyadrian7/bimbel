<?php
namespace Bimbel\Pengeluaran\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Guru\Model\Guru;
use Bimbel\Pengeluaran\Model\Pengeluaran;
use Illuminate\Database\Capsule\Manager as DB;

class Gaji extends BaseModel
{
    protected $fillable = [
        'guru_id', 'total_siswa', 
        'potongan', 'sub_total', 'tunjangan', 'komisi', 'total',
        'tanggal', 'pengeluaran_id', "bulan", "tahun"
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

        $tanggal_gaji = $this->getTanggalGaji($attributes['tanggal']);
        $tanggal_gaji = explode("-", $tanggal_gaji);

        $attributes['total_siswa'] = $guru->siswa->count();
        $attributes['potongan'] = $komisi['potongan'];
        $attributes['sub_total'] = $komisi['sub_total'];
        $attributes['komisi'] = $komisi['komisi'];
        $attributes['tunjangan'] = $tunjangan;
        $attributes['total'] = $komisi['komisi'] + $tunjangan;
        $attributes['bulan'] = $tanggal_gaji[1];
        $attributes['tahun'] = $tanggal_gaji[0];
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
        $tanggal_gaji = $year . "-" . $month . "-1";
        $query = $this->queryLainLain($tanggal_gaji);
        $query->where('tagihan.guru_id', $guru_id);

        return $query->get();
    }

    public function getTagihanDetailIuran($guru_id, $year, $month)
    {
        $tanggal_gaji = $year . "-" . $month . "-1";

        $query = $this->queryIuran($tanggal_gaji);
        $query->where('tagihan.guru_id', $guru_id)->where('tagihan_detail.komisi', ">", 0);
        $query = $query->get();

        return $query;
    }

    public function getTunjangan($guru_id)
    {
        $tunjangan = new \Bimbel\Guru\Model\TunjanganGuru();
        $tunjangan = $tunjangan->where('guru_id', $guru_id)->get();

        return $tunjangan;
    }

    public function getTanggalGaji($tanggal)
    {
        $tanggal = new \DateTime($tanggal);
        $tanggal->modify("-1 month");
        
        return $tanggal->format("Y-m");
    }



    public function baseQuery()
    {
        $query = DB::table('tagihan_detail')
            ->select(
                'orang.nama AS nama_siswa', 'tagihan.code AS kode_tagihan', 'tagihan.tanggal AS tanggal_tagihan',
                'tagihan.tanggal_lunas AS tanggal_lunas', 'tagihan_detail.nama AS nama_item', 'tagihan_detail.total AS harga_total', 'tagihan_detail.qty AS qty',
                'kursus.nama AS kursus', 'transaksi.jenis_pembayaran AS jenis_pembayaran', 'tagihan.kursus_id', 'tagihan_detail.potongan AS potongan',
                'tagihan_detail.sub_total AS sub_total', 'tagihan.guru_id',
                DB::raw('
                    CASE WHEN pembiayaan.jenis_komisi = "s" THEN
                        siswa.komisi
                    ELSE
                        pembiayaan.nominal
                    END
                AS persen_komisi'), DB::raw('(tagihan_detail.total / IFNULL(tagihan_detail.bulan, 1)) AS total_terbagi')
            )
            ->join('tagihan', 'tagihan.id', 'tagihan_detail.tagihan_id')
            ->join('siswa', 'siswa.id', 'tagihan.siswa_id')
            ->join('orang', 'orang.id', 'siswa.orang_id')
            ->join('kursus', 'kursus.id', 'tagihan.kursus_id')
            ->join('pembiayaan', 'pembiayaan.id', 'tagihan_detail.pembiayaan_id')
            ->leftJoin('transaksi', function($join) {
                $join->on('transaksi.tagihan_id', 'tagihan.id')
                    ->on('transaksi.id', DB::raw('(SELECT id FROM transaksi WHERE tagihan_id = tagihan.id ORDER BY id DESC LIMIT 1)'));
            });

        return $query;
    }

    /*
        case untuk iuran:
        1. lunas sebelum bulannya
        2. lunas tepat pada bulannya
        3. lunas telat
    */
    public function queryIuran($start_date, $tagihan_status = 'l')
    {
        $tanggal_gaji = new \DateTime($start_date);
        $tanggal_gaji = $tanggal_gaji->format("Y-m-d");

        $query = $this->baseQuery();
        $query->addSelect(['komisi' => DB::raw('
            CASE 
            WHEN year(tagihan.tanggal_lunas) = year(' . $tanggal_gaji . ') AND month(tagihan.tanggal_lunas) = month(' . $tanggal_gaji . ')  THEN
                (TIMESTAMPDIFF(MONTH, tagihan.tanggal_lunas, tagihan_detail.tanggal_iuran_mulai) + 1 ) * tagihan_detail.komisi
            WHEN year(tagihan.tanggal_lunas) > year(' . $tanggal_gaji . ') OR month(tagihan.tanggal_lunas) > month(' . $tanggal_gaji . ')  THEN
                0
            ELSE
                tagihan_detail.komisi
            END AS komisi
        ')]);

        $query
            ->where('tagihan_detail.system', true)
            ->whereDate("tagihan_detail.tanggal_iuran_mulai", "<=", $tanggal_gaji)
            ->whereDate("tagihan_detail.tanggal_iuran_berakhir", ">=", $tanggal_gaji)
            ->where('tagihan.status', $tagihan_status)
        ;

        // if ($tagihan_status == 'l')
        // {
        //     $query
        //         ->whereMonth("tagihan.tanggal_lunas", "<=", DB::raw("month('" . $tanggal_gaji . "')"))
        //         ->whereYear("tagihan.tanggal_lunas", "<=", DB::raw("year('" . $tanggal_gaji . "')"));
        // }

        return $query;
    }

    public function queryLainLain($start_date)
    {
        $tanggal_gaji = new \DateTime($start_date);
        $month = $tanggal_gaji->format('m');
        $year = $tanggal_gaji->format('Y');

        $query = $this->baseQuery();

        $query->addSelect(['komisi' => DB::raw('tagihan_detail.komisi AS komisi')]);
        
        $query
            ->where(function($q) {
                $q->where('tagihan_detail.system', false)->orWhereNull('tagihan_detail.system');
            })
            ->whereMonth("tagihan.tanggal_lunas", $month)
            ->whereYear("tagihan.tanggal_lunas", $year)
            ->where('tagihan.status', 'l')
        ;

        return $query;
    }
}