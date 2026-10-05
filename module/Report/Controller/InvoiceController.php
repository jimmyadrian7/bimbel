<?php
namespace Bimbel\Report\Controller;

use \Bimbel\Report\Controller\BaseReportController;
use Ngekoding\Terbilang\Terbilang;
use Bimbel\Report\Helper\KwitansiTemplate;

class InvoiceController extends BaseReportController
{
    public function getTagihan($request, $args, &$response)
    {
        $result = [];

        try
        {
            $tagihan = new \Bimbel\Pembayaran\Model\Tagihan();
            $tagihan = $tagihan->find($args['tagihan_id']);

            $data = [
                'title' => true,
                'judul' => 'Invoice',
                'tagihan' => $tagihan,
                'siswa' => $tagihan->siswa->orang->nama,
                'nomor' => $tagihan->code
            ];

            $result = $this->toPdf("Report/View/tagihan.twig", $data);
        }
        catch(\Error $e)
        {
            $result = $this->container->get('error')($e, $response);
        }
        
        return $result;
    }

    public function getTabunganAset($request, $args, &$response)
    {
        $result = [];

        try
        {
            $tabungan_aset = new \Bimbel\Pengeluaran\Model\TabunganAset();
            $tabungan_aset = $tabungan_aset->find($args['tabungan_aset_id']);

            $cicilan_asets = $tabungan_aset->cicilan_aset()->where('status', 's')->get();
            $penarikans = $tabungan_aset->penarikan()->where('status', 's')->get();

            $data = [
                'title' => true,
                'judul' => 'Invoice',
                'nomor' => $tabungan_aset->code,
                'tabungan_aset' => $tabungan_aset,
                'cicilan_asets' => $cicilan_asets,
                'penarikans' => $penarikans,
                'total_cicilan' => $cicilan_asets->sum('nominal'),
                'total_penarikan' => $penarikans->sum('nominal')
            ];

            $result = $this->toPdf("Report/View/invoice.twig", $data);
        }
        catch(\Error $e)
        {
            $result = $this->container->get('error')($e, $response);
        }
        
        return $result;
    }

    public function getKwitansi($request, $args, &$response)
    {
        $result = [];

        try
        {
            $tagihan = new \Bimbel\Pembayaran\Model\Tagihan();
            $tagihan = $tagihan->find($args['tagihan_id']);

            $result = $this->renderKwitansi($this->getKwitansiData($tagihan));
        }
        catch(\Error $e)
        {
            $result = $this->container->get('error')($e, $response);
        }
        
        return $result;
    }

    /**
     * Everything the kwitansi templates may use. Only plain arrays/strings are
     * passed (no models): the sections an admin can edit run in a Twig sandbox
     * and must not be able to reach relations or methods. Templates read these
     * the same way as before (tagihan.status, program.nama ...).
     * Keep in sync with KwitansiTemplate::sampleContext()/variables().
     */
    public function getKwitansiData($tagihan)
    {
        $tmpt_kursus = $tagihan->kursus;
        if ($tmpt_kursus->logo_bank)
        {
            $logo_bank = 'data:' . $tmpt_kursus->logo_bank->filetype . ';base64, ' . $tmpt_kursus->logo_bank->base64;
        }
        else
        {
            $logo_bank = "";
        }

        $stamp_img = $this->getImage('stamp.png');
        
        $terbilang = Terbilang::convert($tagihan->total) . " rupiah";
        $untuk = [];
        $untuk_spp = [];

        $program_belajars = $this->getProgramBelajars();
        $program_belajar_is_other = !empty($tagihan->program_belajar) && !in_array($tagihan->program_belajar, array_column($program_belajars, 'nama'));

        // foreach ($tagihan->tagihan_detail as $key => $tagihan_detail) {
        //     if ($tagihan_detail->kategori_pembiayaan == 's')
        //     {
        //         if ($tagihan_detail->tanggal_iuran_mulai == $tagihan_detail->tanggal_iuran_berakhir)
        //         {
        //             $tanggal_iuran = date('F Y', strtotime($tagihan_detail->tanggal_iuran_mulai));
        //         }
        //         else
        //         {
        //             $tanggal_iuran = date('F Y', strtotime($tagihan_detail->tanggal_iuran_mulai)) . " - " . date('F Y', strtotime($tagihan_detail->tanggal_iuran_berakhir));
        //         }
        //         array_push($untuk_spp, "Iuran " . $tanggal_iuran);
        //     }
        //     else
        //     {
        //         array_push($untuk, $tagihan_detail->nama);
        //     }
        // }

        $untuk = implode(", ", $untuk);
        $untuk_spp = implode(", ", $untuk_spp);

        return [
            'tagihan' => KwitansiTemplate::only($tagihan->getAttributes(), KwitansiTemplate::TAGIHAN_KEYS),
            'kursus' => KwitansiTemplate::only($tmpt_kursus->getAttributes(), KwitansiTemplate::KURSUS_KEYS),
            'siswa' => $tagihan->siswa->orang->nama,
            'nomor' => $tagihan->code,
            'smileimage' => $this->getImage('smile-icon.png'),
            'logo_bank' => $logo_bank,
            'untuk' => $untuk,
            'untuk_spp' => $untuk_spp,
            'terbilang' => ucwords($terbilang),
            'stamp_img' => $stamp_img,
            'program_belajars' => $program_belajars,
            'program_belajar_is_other' => $program_belajar_is_other,
            'report_info_data' => $this->getReportInfoData(),
        ];
    }

    /** Master program belajar as plain arrays (id, kode, nama, nama_mandarin). */
    protected function getProgramBelajars()
    {
        $program_belajar = new \Bimbel\Master\Model\ProgramBelajar();
        $rows = [];

        foreach ($program_belajar->orderBy('id', 'ASC')->get() as $program)
        {
            $rows[] = KwitansiTemplate::only($program->getAttributes(), KwitansiTemplate::PROGRAM_KEYS);
        }

        return $rows;
    }

    /**
     * Plain copy of the Report Profile for edited sections (toPdf() itself
     * injects the model under `report_info`, which the default template uses).
     */
    protected function getReportInfoData()
    {
        $report_info = \Bimbel\Master\Model\ReportInfo::find(1);

        return $report_info ? KwitansiTemplate::only($report_info->getAttributes(), ['alamat', 'no_hp', 'email']) : [];
    }

    /**
     * Renders the kwitansi PDF with what admins have customised (see
     * KwitansiTemplate): edited sections and/or the visual layout. With nothing
     * customised this is exactly the old behaviour.
     *
     * @param array|null $overrides [section => text]; null loads the saved ones
     * @param bool $strict true (preview) rethrows template errors; false (real
     *                     printing) falls back to the default template instead,
     *                     because a broken custom template must never stop anyone
     *                     from printing a receipt.
     * @param array|null|false $layout normalized visual layout; null = use the
     *                     standard kwitansi.twig; false loads the saved one
     */
    protected function renderKwitansi($data, $overrides = null, $strict = false, $layout = false)
    {
        if ($overrides === null)
        {
            $overrides = KwitansiTemplate::loadOverrides();
        }

        if ($layout === false)
        {
            $layout = KwitansiTemplate::loadLayout();
        }

        if (!empty($overrides) || $layout !== null)
        {
            $options = $this->container->get("pdf")->getOptions();
            $remote = $options->getIsRemoteEnabled();

            $view = KwitansiTemplate::VIEW;
            $custom = $data;
            if ($layout !== null)
            {
                $view = KwitansiTemplate::VIEW_LAYOUT;
                // program_kustom: hand-written program code wins over the program grid setting
                $custom['layout'] = $layout + ['program_kustom' => array_key_exists('program', $overrides)];
            }

            try
            {
                // Edited HTML must not be able to make the server fetch anything:
                // every image the kwitansi uses is an embedded data: URI.
                $options->setIsRemoteEnabled(false);

                return KwitansiTemplate::withOverrides($this->container->get("twig"), $overrides, function () use ($view, $custom) {
                    return $this->toPdf($view, $custom);
                });
            }
            catch (\Twig\Error\Error $e)
            {
                if ($strict)
                {
                    throw $e;
                }

                error_log('[kwitansi-template] custom template failed, default used: ' . KwitansiTemplate::describeError($e));
            }
            finally
            {
                $options->setIsRemoteEnabled($remote);
            }
        }

        return $this->toPdf(KwitansiTemplate::VIEW, $data);
    }

    /**
     * The kwitansi as HTML for the template editor's canvas: the same page and the same
     * data renderKwitansi() gives dompdf, but always through layout.twig (with the default
     * settings that is the standard page) and with the editor hooks switched on
     * (data-kw-* attributes, see layout.twig). Strict: template errors are thrown.
     *
     * @param array $overrides [section => text] to render instead of the saved ones
     * @param array $layout normalized visual layout (KwitansiTemplate::normalizeLayout())
     */
    protected function renderKwitansiHtml($data, array $overrides, array $layout)
    {
        $custom = $data;
        $custom['layout'] = $layout + ['program_kustom' => array_key_exists('program', $overrides)];
        $custom['kw_editor'] = true;

        return KwitansiTemplate::withOverrides($this->container->get("twig"), $overrides, function () use ($custom) {
            return $this->buildHtml(KwitansiTemplate::VIEW_LAYOUT, $custom);
        });
    }
}