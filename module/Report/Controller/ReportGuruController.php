<?php
namespace Bimbel\Report\Controller;

use \Bimbel\Report\Controller\BaseReportController;
use Illuminate\Support\Collection;

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

            $tagihan_details = $gaji->getTunjangan($postData['guru_id']);
            $tagihan_details = $tagihan_details->map->format();
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
            
            $data = [
                'judul' => "Pendapatan " . $guru->orang->nama,
                'periode' => $this->convertDate($postData['start_date'], "F Y"),
                'jenis_pembayaran_list' => $jenis_pembayaran_list,
                'kursus_lists' => $kursus_lists,
                "data" => $data,
                "total_pendapatan" => $total_pendapatan
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

            // Komisi iuran Lunas
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

            // Komisi iuran Proses
            $tagihan_details = $gaji->queryIuran($tanggal_gaji, 'p');
            $tagihan_details->where('tagihan.guru_id', $postData['guru_id'])->where('tagihan_detail.kategori_pembiayaan', 's');
            if (array_key_exists('tempat_kursus', $postData) && !empty($postData['tempat_kursus']))
            {
                $tagihan_details->where('tagihan.kursus_id', $postData['tempat_kursus']);
            }

            if ($tagihan_details->count() > 0)
            {
                $data_row['Proses'] = $tagihan_details->get();
            }

            // Komisi iuran Menunggu Verifikasi
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
}