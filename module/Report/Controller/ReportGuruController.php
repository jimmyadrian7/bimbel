<?php
namespace Bimbel\Report\Controller;

use \Bimbel\Report\Controller\BaseReportController;
use Illuminate\Support\Collection;
use \Bimbel\Guru\Model\PotonganGaji;
use Illuminate\Database\Capsule\Manager as DB;

class ReportGuruController extends BaseReportController
{
    public function getPendapatan($request, $args, $response)
    {
        $result = [];

        try
        {
            $postData = $request->getParsedBody();
            $data_row = collect([]);

            $guru = new \Bimbel\Guru\Model\Guru();
            $gaji = new \Bimbel\Pengeluaran\Model\Gaji();
            $tagihans = new \Bimbel\Pembayaran\Model\Tagihan();
            $tagihan_details = new \Bimbel\Pembayaran\Model\TagihanDetail();
            $transaksi = new \Bimbel\Pembayaran\Model\Transaksi();
            $potongan_gaji = new PotonganGaji();
            $asisten_guru = new PotonganGaji();

            $jenis_pembayaran_list = [];
            foreach ($transaksi->jenis_pembayaran_enum as $key => $value) {
                $jenis_pembayaran_list[$value['value']] = $value['label'];
            }

            $tanggal = explode("-", $postData['start_date']);
            $year = $tanggal[0];
            $month = $tanggal[1];
            $total_pendapatan = 0;
            
            $guru = $guru->find($postData['guru_id']);

            // Komisi selain iuran
            $tagihan_details = $gaji->queryLainLain($postData['start_date'] . "-01");
            $tagihan_details->where('tagihan.guru_id', $postData['guru_id']);
            if (array_key_exists('tempat_kursus', $postData) && !empty($postData['tempat_kursus']))
            {
                $tagihan_details->where('tagihan.kursus_id', $postData['tempat_kursus']);
            }
            $data_row = $data_row->merge($tagihan_details->get());

            // Komisi iuran
            $tagihan_details = $gaji->queryIuran($postData['start_date'] . "-01");
            $tagihan_details->where('tagihan.guru_id', $postData['guru_id'])->where('tagihan_detail.komisi', ">", 0);
            if (array_key_exists('tempat_kursus', $postData) && !empty($postData['tempat_kursus']))
            {
                $tagihan_details->where('tagihan.kursus_id', $postData['tempat_kursus']);
            }
            $data_row = $data_row->merge($tagihan_details->get());

            $jenis_pembayaran = "tf";

            if (isset($data_row[0]))
            {
                $jenis_pembayaran = $data_row[0]->jenis_pembayaran;
            }

            $tagihan_details = $gaji->getTunjangan($postData['guru_id']);
            $tagihan_details = $tagihan_details->map->format($postData['start_date'] . "-01", $jenis_pembayaran);
            $data_row = $data_row->merge($tagihan_details);
            
            // Sort by Komisi
            $data_row = $data_row->sortBy('persen_komisi');
            $total_pendapatan = $data_row->sum('komisi');

            $data = $data_row->groupBy(['jenis_pembayaran']);
            // $data_row = $data_row->toArray();

            $kursus_lists = [];

            foreach($data as $d)
            {
                $kursus_lists[] = join(", ", $d->pluck('kursus')->unique()->toArray());
            }

            $potongan_gaji = $potongan_gaji
                // ->select(DB::raw('SUM(nominal) AS nominal'))
                ->where('guru_id', $postData['guru_id'])
                ->whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->get();

            // $asisten_guru = $asisten_guru
            //     ->where('guru_id', $postData['guru_id'])
            //     ->whereYear('tanggal', $year)
            //     ->whereMonth('tanggal', $month)
            //     ->first();

            // if ($asisten_guru)
            // {
            //     $asisten_guru = $asisten_guru->asisten_guru;
            // }
            // else
            // {
            //     $asisten_guru = "";
            // }

            // $potongan_gaji = $potongan_gaji->nominal ?? 0;
            $total_potongan_gaji = $potongan_gaji->sum('nominal');
            
            $data = [
                'judul' => "Pendapatan " . $guru->orang->nama,
                'periode' => $this->convertDate($postData['start_date'], "F Y"),
                'jenis_pembayaran_list' => $jenis_pembayaran_list,
                'kursus_lists' => $kursus_lists,
                "data" => $data,
                "total_pendapatan" => $total_pendapatan,
                "potongan_gaji" => $potongan_gaji,
                "total_potongan_gaji" => $total_potongan_gaji,
                // 'asisten_guru' => $asisten_guru,
                'guru' => $guru
            ];

            $result = $this->toPdf("Report/View/pendapatan_guru.twig", $data);
        }
        catch(\Error $e)
        {
            $result = $this->container->get('error')($e, $response);
            $result = json_encode($result);
        }

        return $result;
    }

    public function getIuran($request, $args, $response)
    {
        $result = [];

        try
        {
            $postData = $request->getParsedBody();
            $data_row = [];

            $guru = new \Bimbel\Guru\Model\Guru();
            $gaji = new \Bimbel\Pengeluaran\Model\Gaji();
            $tagihans = new \Bimbel\Pembayaran\Model\Tagihan();
            $tagihan_details = new \Bimbel\Pembayaran\Model\TagihanDetail();
            $transaksi = new \Bimbel\Pembayaran\Model\Transaksi();


            $tanggal_gaji = $postData['start_date'] . '-01';
            
            $guru = $guru->find($postData['guru_id']);
            $status = $postData['status'] ?? null;

            // Komisi iuran Lunas
            if ($status == 'l' || empty($status))
            {
                $tagihan_details = $gaji->queryIuran($tanggal_gaji, 'l');
                $tagihan_details->where('tagihan.guru_id', $postData['guru_id'])->where('tagihan_detail.kategori_pembiayaan', 's');
                if (array_key_exists('tempat_kursus', $postData) && !empty($postData['tempat_kursus']))
                {
                    $tagihan_details->where('tagihan.kursus_id', $postData['tempat_kursus']);
                }
                if ($tagihan_details->count() > 0)
                {
                    $data_row['Lunas'] = $tagihan_details->get();
                }
            }

            // Komisi iuran Proses
            if ($status == 'p' || empty($status))
            {
                $tagihan_details = $gaji->queryIuran($tanggal_gaji, 'p');
                $tagihan_details->where('tagihan.guru_id', $postData['guru_id'])->where('tagihan_detail.kategori_pembiayaan', 's');
                if (array_key_exists('tempat_kursus', $postData) && !empty($postData['tempat_kursus']))
                {
                    $tagihan_details->where('tagihan.kursus_id', $postData['tempat_kursus']);
                }

                if ($tagihan_details->count() > 0)
                {
                    $data_row['Belum Bayar'] = $tagihan_details->get();
                }
            }

            // Komisi iuran Menunggu Verifikasi
            if ($status == 'c' || empty($status))
            {
                $tagihan_details = $gaji->queryIuran($tanggal_gaji, 'c');
                $tagihan_details->where('tagihan.guru_id', $postData['guru_id'])->where('tagihan_detail.kategori_pembiayaan', 's');
                if (array_key_exists('tempat_kursus', $postData) && !empty($postData['tempat_kursus']))
                {
                    $tagihan_details->where('tagihan.kursus_id', $postData['tempat_kursus']);
                }
                if ($tagihan_details->count() > 0)
                {
                    $data_row['Menunggu Verifikasi'] = $tagihan_details->get();
                }
            }
            
            $data = [
                'judul' => "Iuran " . $guru->orang->nama,
                'periode' => $this->convertDate($postData['start_date'], "F Y"),
                "data" => $data_row
            ];

            $result = $this->toPdf("Report/View/iuran.twig", $data);
        }
        catch(\Error $e)
        {
            $result = $this->container->get('error')($e, $response);
            $result = json_encode($result);
        }

        return $result;
    }

    public function getSlipGaji($request, $args, $response)
    {
        $result = [];

        try
        {
            $postData = $request->getParsedBody();
            $data_row = collect([]);

            $guru = new \Bimbel\Guru\Model\Guru();
            $gaji = new \Bimbel\Pengeluaran\Model\Gaji();
            $potongan_gaji = new PotonganGaji();
            
            $guru = $guru->find($postData['guru_id']);
            $status = null;
            $total_gaji = 0;

            $tanggal_gaji = \DateTime::createFromFormat("Y-m-d", $postData['start_date']);
            $tahun_gaji = $tanggal_gaji->format('Y');
            $bulan_gaji = $tanggal_gaji->format('m');
            
            // $gaji = $gaji->where('guru_id', $postData['guru_id'])->whereYear('tanggal', $tahun_gaji)->whereMonth('tanggal', $bulan_gaji)->sum('komisi');
            // Komisi selain iuran
            $tagihan_details = $gaji->queryLainLain($tahun_gaji . "-" . $bulan_gaji . "-01");
            $tagihan_details->where('tagihan.guru_id', $postData['guru_id']);
            $data_row = $data_row->merge($tagihan_details->get());

            // Komisi Iuran
            $tagihan_details = $gaji->queryIuran($tahun_gaji . "-" . $bulan_gaji . "-01");
            $tagihan_details->where('tagihan.guru_id', $postData['guru_id'])->where('tagihan_detail.komisi', ">", 0);
            $data_row = $data_row->merge($tagihan_details->get());

            // Potongan Gaji
            $potongan_gaji = $potongan_gaji
                // ->select(DB::raw('SUM(nominal) AS nominal'))
                ->where('guru_id', $postData['guru_id'])
                ->whereYear('tanggal', $tahun_gaji)
                ->whereMonth('tanggal', $bulan_gaji)
                ->get();
            // $potongan_gaji = $potongan_gaji->nominal ?? 0;
            $total_potongan = $potongan_gaji->sum('nominal');
            
            $gaji = $data_row->sum('komisi');
            $tunjangan_guru = $guru->tunjangan_guru;
            $cabang = $guru->kursus->first();
            // $cabang = implode(', ', $cabang);

            $total_tunjangan = $tunjangan_guru->sum(function ($tunjangan_guru) {
                return $tunjangan_guru['nominal'] * $tunjangan_guru['jumlah'];
            });

            $total_diterima = $gaji + $total_tunjangan - $total_potongan;

            $smile_images = $this->getImage('Cus-2.jpg');
            
            $data = [
                'judul' => "SLIP GAJI " . strtoupper($guru->jabatan),
                'periode' => $this->convertDate($postData['start_date'], "F Y"),
                'tanggal' => $this->convertDate($postData['start_date'], "d F Y"),
                'guru' => $guru,
                'cabang' => $cabang->nama,
                'gaji_pokok' => $gaji,
                'tunjangan_guru' => $tunjangan_guru,
                'kursus' => $cabang,
                'total_tunjangan' => $total_tunjangan,
                'potongan_gaji' => $potongan_gaji,
                'total_diterima' => $total_diterima,
                'smile_images' => $smile_images,
            ];

            $result = $this->toPdf("Report/View/slip_gaji.twig", $data, "background slip gaji.jpg", 'potrait');
        }
        catch(\Error $e)
        {
            $result = $this->container->get('error')($e, $response);
            $result = json_encode($result);
        }

        return $result;
    }
}