<?php
namespace Bimbel\Report\Controller;

use \Bimbel\Report\Controller\BaseReportController;

class TagihanExportController extends BaseReportController
{
    protected $status_label = [
        'p' => 'Proses',
        'c' => 'Menunggu Verifikasi',
        'l' => 'Lunas'
    ];

    private function fetchTagihans($request)
    {
        $postData = $request->getParsedBody();
        if (empty($postData))
        {
            $postData = [];
        }

        $tagihan = new \Bimbel\Pembayaran\Model\Tagihan();
        $obj = $tagihan->with('siswa', 'siswa.orang');

        $condition = [];
        $sort = [];

        $session = new \Bimbel\Master\Model\Session();
        if (!$session->isSuperUser())
        {
            $siswa_ids = $session->getSiswaIds();
            $condition[] = ['siswa_id', 'in', $siswa_ids];
        }

        if (array_key_exists('search', $postData) && !empty($postData['search']))
        {
            $condition[] = [$tagihan->searchField, 'like', '%' . $postData['search'] . '%'];
        }

        if (array_key_exists('filter', $postData) && !empty($postData['filter']))
        {
            foreach ($postData['filter'] as $filter)
            {
                if (empty($filter['field']) || empty($filter['operation']))
                {
                    continue;
                }

                $value = $filter['value'];

                if ($filter['operation'] == 'like')
                {
                    $value = "%" . $value . "%";
                }

                $condition[] = [$filter['field'], $filter['operation'], $value];
            }
        }

        if (array_key_exists('sort', $postData) && !empty($postData['sort']))
        {
            foreach ($postData['sort'] as $s)
            {
                $sort[] = [$s['field'], $s['type']];
            }
        }

        return $tagihan->fetchAllData($condition, $obj, false, 1, $sort);
    }

    public function exportPdf($request, $args, &$response)
    {
        $result = [];

        try
        {
            $tagihans = $this->fetchTagihans($request);

            $data = [
                'judul' => "Data Tagihan",
                'tagihans' => $tagihans,
                'status_label' => $this->status_label,
                'total_sub_total' => $tagihans->sum('sub_total'),
                'total_potongan' => $tagihans->sum('potongan'),
                'total' => $tagihans->sum('total'),
                'total_hutang' => $tagihans->sum('hutang')
            ];

            $result = $this->toPdf("Report/View/tagihan_list.twig", $data);
        }
        catch (\Error $e)
        {
            $result = $this->container->get('error')($e, $response);
        }

        return $result;
    }

    public function exportExcel($request, $args, &$response)
    {
        $result = [];

        try
        {
            $tagihans = $this->fetchTagihans($request);

            $header = [
                'Kode', 'Tanggal', 'Siswa', 'Guru', 'Sub Total', 'Potongan', 'Total', 'Hutang', 'Status'
            ];

            $rows = [$header];

            foreach ($tagihans as $tagihan)
            {
                $rows[] = [
                    $tagihan->code,
                    $this->convertDate($tagihan->tanggal),
                    $tagihan->siswa && $tagihan->siswa->orang ? $tagihan->siswa->orang->nama : '-',
                    $tagihan->guru && $tagihan->guru->orang ? $tagihan->guru->orang->nama : '-',
                    $tagihan->sub_total,
                    $tagihan->potongan,
                    $tagihan->total,
                    $tagihan->hutang,
                    array_key_exists($tagihan->status, $this->status_label) ? $this->status_label[$tagihan->status] : $tagihan->status
                ];
            }

            $excel = $this->container->get('excel');
            $excel->writeSheet($rows, 'Tagihan');
            $result = $excel->writeToString();
            $result = ['data' => base64_encode($result)];
            $result = json_encode($result);
        }
        catch (\Error $e)
        {
            $result = $this->container->get('error')($e, $response);
        }

        return $result;
    }

    /*
        Same computation as Siswa\Controller\FetchController::previewMassGenerateTagihan(),
        kept here (rather than reused over HTTP) so it can feed the PDF/Excel
        exports below. This is read-only: Siswa::previewTagihan() never creates
        a Tagihan or marks any iuran as billed.
    */
    private function buildPreviewData($tanggal)
    {
        $siswas = new \Bimbel\Siswa\Model\Siswa();
        $siswas = $siswas->with('orang')->where('status', '=', 'a')->get();

        $preview = [];
        $grand_total = 0;

        foreach ($siswas as $siswa)
        {
            try
            {
                $tagihan_detail = $siswa->previewTagihan($tanggal);
            }
            catch (\Error $e)
            {
                continue;
            }

            if (count($tagihan_detail) === 0)
            {
                continue;
            }

            $total = 0;
            foreach ($tagihan_detail as $detail)
            {
                $total += $detail['nominal'] * $detail['qty'];
            }

            $preview[] = [
                'siswa_id' => $siswa->id,
                'nama' => $siswa->orang->nama,
                'items' => $tagihan_detail,
                'total' => $total
            ];

            $grand_total += $total;
        }

        return ['data' => $preview, 'grand_total' => $grand_total];
    }

    private function formatRincian($items)
    {
        $parts = [];

        foreach ($items as $item)
        {
            $part = $item['nama'];

            if (!empty($item['qty']) && $item['qty'] > 1)
            {
                $part .= ' (x' . $item['qty'] . ')';
            }

            $parts[] = $part;
        }

        return implode(', ', $parts);
    }

    public function exportPreviewPdf($request, $args, &$response)
    {
        $result = [];

        try
        {
            $postData = $request->getParsedBody();
            $tanggal = !empty($postData['tanggal']) ? $postData['tanggal'] : false;

            $preview = $this->buildPreviewData($tanggal);

            $data = [
                'judul' => "Preview Tagihan",
                'tanggal' => $tanggal ? $this->convertDate($tanggal) : '-',
                'preview' => $preview['data'],
                'grand_total' => $preview['grand_total']
            ];

            $result = $this->toPdf("Report/View/tagihan_preview.twig", $data);
        }
        catch (\Error $e)
        {
            $result = $this->container->get('error')($e, $response);
        }

        return $result;
    }

    public function exportPreviewExcel($request, $args, &$response)
    {
        $result = [];

        try
        {
            $postData = $request->getParsedBody();
            $tanggal = !empty($postData['tanggal']) ? $postData['tanggal'] : false;

            $preview = $this->buildPreviewData($tanggal);

            $header = ['Siswa', 'Rincian', 'Total'];
            $rows = [$header];

            foreach ($preview['data'] as $row)
            {
                $rows[] = [
                    $row['nama'],
                    $this->formatRincian($row['items']),
                    $row['total']
                ];
            }

            $rows[] = ['', 'Grand Total', $preview['grand_total']];

            $excel = $this->container->get('excel');
            $excel->writeSheet($rows, 'Preview Tagihan');
            $result = $excel->writeToString();
            $result = ['data' => base64_encode($result)];
            $result = json_encode($result);
        }
        catch (\Error $e)
        {
            $result = $this->container->get('error')($e, $response);
        }

        return $result;
    }
}
