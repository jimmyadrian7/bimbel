<?php
namespace Bimbel\Report\Controller;

use Bimbel\Report\Helper\KwitansiTemplate;

/**
 * API behind Konfigurasi > Template Kwitansi.
 *
 * Extends InvoiceController so the preview goes through exactly the same data
 * building and PDF rendering as a real receipt (what you preview is what prints).
 * The template is global (all branches), so only a Super Admin may use it.
 */
class KwitansiTemplateController extends InvoiceController
{
    const JSON_FLAGS = JSON_INVALID_UTF8_SUBSTITUTE;

    /** Sections with their current + default text, plus the help the editor shows. */
    public function getState($request, $args, &$response)
    {
        try
        {
            $this->requireSuperUser();

            $result = json_encode($this->buildState(), self::JSON_FLAGS);
        }
        catch(\Error $e)
        {
            $result = json_encode($this->container->get('error')($e, $response));
        }

        return $result;
    }

    /**
     * Renders the PDF for the (unsaved) text in the editor.
     *
     * Problems in the template are returned in the body with HTTP 200
     * ({error, errors: {section: message}}) instead of an HTTP error, so the
     * editor can show them inline next to the text rather than as a toast.
     */
    public function preview($request, $args, &$response)
    {
        try
        {
            $this->requireSuperUser();

            $prepared = $this->prepareRender($this->getBody($request));
            if (isset($prepared['problem']))
            {
                return json_encode($prepared['problem']);
            }

            // A layout equal to the default = the standard kwitansi.twig.
            $layout = $prepared['layout'];
            if ($layout !== null && KwitansiTemplate::isDefaultLayout($layout))
            {
                $layout = null;
            }

            try
            {
                $pdf = json_decode($this->renderKwitansi($prepared['data'], $prepared['changed'], true, $layout), true);
            }
            catch (\Twig\Error\Error $e)
            {
                return json_encode(['error' => KwitansiTemplate::describeError($e), 'errors' => []]);
            }

            $result = json_encode([
                'data' => $pdf['data'],
                'pages' => $this->container->get("pdf")->getCanvas()->get_page_count(),
            ]);
        }
        catch(\Error $e)
        {
            $result = json_encode($this->container->get('error')($e, $response));
        }

        return $result;
    }

    /**
     * The page the editor's canvas shows: the same HTML (same data, same edited sections, same
     * layout) that preview() gives dompdf, without the PDF step, plus the editor script and a
     * Content-Security-Policy (see KwitansiTemplate::canvasDocument()). Same request and
     * error conventions as preview().
     */
    public function html($request, $args, &$response)
    {
        try
        {
            $this->requireSuperUser();

            $prepared = $this->prepareRender($this->getBody($request));
            if (isset($prepared['problem']))
            {
                return json_encode($prepared['problem']);
            }

            $layout = $prepared['layout'] === null ? KwitansiTemplate::defaultLayout() : $prepared['layout'];

            try
            {
                $html = $this->renderKwitansiHtml($prepared['data'], $prepared['changed'], $layout);
            }
            catch (\Twig\Error\Error $e)
            {
                return json_encode(['error' => KwitansiTemplate::describeError($e), 'errors' => []]);
            }

            $result = json_encode(['html' => KwitansiTemplate::canvasDocument($html)], self::JSON_FLAGS);
        }
        catch(\Error $e)
        {
            $result = json_encode($this->container->get('error')($e, $response));
        }

        return $result;
    }

    /** Saves the given sections (those equal to the default are reset) and returns the new state. */
    public function save($request, $args, &$response)
    {
        try
        {
            $session = $this->requireSuperUser();

            if (!KwitansiTemplate::isInstalled())
            {
                throw new \Error('Tabel template belum dibuat. Jalankan Fix Data patch 2/6 terlebih dahulu.', 500);
            }

            $sources = $this->readSections($this->getBody($request));

            $errors = $this->lintSections($sources);
            if (!empty($errors))
            {
                $messages = [];
                foreach ($errors as $key => $message)
                {
                    $messages[] = KwitansiTemplate::SECTIONS[$key]['label'] . ' - ' . $message;
                }

                throw new \Error('Template tidak disimpan. ' . implode(' | ', $messages), 422);
            }

            $body = $this->getBody($request);
            $layout = array_key_exists('layout', $body) ? $this->readLayout($body) : null;

            KwitansiTemplate::saveOverrides($sources, $session->get('user_id'), $layout);

            $result = json_encode($this->buildState(), self::JSON_FLAGS);
        }
        catch(\Error $e)
        {
            $result = json_encode($this->container->get('error')($e, $response));
        }

        return $result;
    }

    // ------------------------------------------------------------------

    protected function requireSuperUser()
    {
        // Throws (501) when nobody is logged in.
        $session = new \Bimbel\Master\Model\Session();

        if (!$session->isSuperUser())
        {
            throw new \Error('Hanya Super Admin yang dapat mengubah template kwitansi.', 403);
        }

        return $session;
    }

    protected function getBody($request)
    {
        $body = $request->getParsedBody();

        return is_array($body) ? $body : [];
    }

    protected function buildState()
    {
        $overrides = KwitansiTemplate::loadOverrides();
        $sections = [];

        foreach (KwitansiTemplate::sections() as $key => $definition)
        {
            $default = KwitansiTemplate::defaultSource($key);
            $custom = array_key_exists($key, $overrides);

            $sections[] = [
                'key' => $key,
                'label' => $definition['label'],
                'keterangan' => $definition['keterangan'],
                'default' => $default,
                'konten' => $custom ? KwitansiTemplate::normalize($overrides[$key]) : $default,
                'diubah' => $custom,
            ];
        }

        $saved = KwitansiTemplate::loadLayout();
        $blocks = [];
        foreach (KwitansiTemplate::BLOCKS as $key => $label)
        {
            $blocks[] = ['key' => $key, 'label' => $label];
        }

        return [
            'terpasang' => KwitansiTemplate::isInstalled(),
            'layout' => $saved === null ? KwitansiTemplate::defaultLayout() : $saved,
            'layout_standar' => KwitansiTemplate::defaultLayout(),
            'blok' => $blocks,
            'batas' => KwitansiTemplate::LAYOUT_LIMITS,
            'sections' => $sections,
            'contoh' => KwitansiTemplate::examples(),
            'variabel' => KwitansiTemplate::variables(),
            'diizinkan' => [
                'tag' => KwitansiTemplate::ALLOWED_TAGS,
                'filter' => KwitansiTemplate::ALLOWED_FILTERS,
                'fungsi' => KwitansiTemplate::ALLOWED_FUNCTIONS,
            ],
            'jumlah_program' => count($this->getProgramBelajars()),
        ];
    }

    /** [known section key => normalized text] from the request; everything else is ignored. */
    protected function readSections(array $body)
    {
        $sections = isset($body['sections']) ? $body['sections'] : [];

        if (!is_array($sections))
        {
            throw new \Error('Data template tidak valid.', 400);
        }

        $sources = [];
        foreach ($sections as $key => $source)
        {
            if (!KwitansiTemplate::isSection($key))
            {
                continue;
            }

            if (!is_string($source))
            {
                throw new \Error('Isi bagian "' . $key . '" harus berupa teks.', 400);
            }

            $sources[$key] = KwitansiTemplate::normalize($source);
        }

        return $sources;
    }

    /** The visual layout from the request, normalized (unknown keys dropped, numbers clamped). */
    protected function readLayout(array $body)
    {
        if (!is_array($body['layout']))
        {
            throw new \Error('Data layout tidak valid.', 400);
        }

        return KwitansiTemplate::normalizeLayout($body['layout']);
    }

    /**
     * What preview() and html() both need from the request: the lint result, the sample data,
     * the sections that differ from the default and the layout (absent from the request = the
     * saved one; null = none saved).
     *
     * @return array ['problem' => body to answer with] or ['data', 'changed', 'layout']
     */
    protected function prepareRender(array $body)
    {
        $sources = $this->readSections($body);

        $errors = $this->lintSections($sources);
        if (!empty($errors))
        {
            return ['problem' => ['error' => 'Template belum valid.', 'errors' => $errors]];
        }

        return [
            'data' => $this->getPreviewData($body),
            'changed' => $this->changedSections($sources),
            'layout' => array_key_exists('layout', $body) ? $this->readLayout($body) : KwitansiTemplate::loadLayout(),
        ];
    }

    /** Only sections that differ from the default need to be rendered as overrides. */
    protected function changedSections(array $sources)
    {
        $changed = [];
        foreach ($sources as $key => $source)
        {
            if (!KwitansiTemplate::sameAsDefault($key, $source))
            {
                $changed[$key] = $source;
            }
        }

        return $changed;
    }

    /** [section key => message] for every changed section that does not pass the sandbox. */
    protected function lintSections(array $sources)
    {
        $errors = [];
        $twig = $this->container->get("twig");
        $context = KwitansiTemplate::sampleContext($this->getProgramBelajars());

        foreach ($this->changedSections($sources) as $key => $source)
        {
            $message = KwitansiTemplate::lint($twig, $key, $source, $context);

            if ($message !== null)
            {
                $errors[$key] = $message;
            }
        }

        return $errors;
    }

    /**
     * Data for the preview: a real tagihan when an id is given, otherwise sample
     * values. Program belajar is always the live master list, since a changing
     * number of programs is exactly what makes the layout break.
     */
    protected function getPreviewData(array $body)
    {
        $tagihan_id = isset($body['tagihan_id']) ? (int) $body['tagihan_id'] : 0;

        if ($tagihan_id > 0)
        {
            $tagihan = (new \Bimbel\Pembayaran\Model\Tagihan())->find($tagihan_id);

            if (!$tagihan)
            {
                throw new \Error('Tagihan dengan ID ' . $tagihan_id . ' tidak ditemukan.', 404);
            }

            return $this->getKwitansiData($tagihan);
        }

        $status = (isset($body['status']) && $body['status'] === 'p') ? 'p' : 'l';
        $data = KwitansiTemplate::sampleContext($this->getProgramBelajars(), $status);

        $data['smileimage'] = $this->getImage('smile-icon.png');
        $data['stamp_img'] = $this->getImage('stamp.png');
        $data['logo_bank'] = $this->getImage('smile.png');
        $data['report_info_data'] = $this->getReportInfoData();

        return $data;
    }
}
