<?php
namespace Bimbel\Report\Helper;

use Illuminate\Database\Capsule\Manager as DB;
use Slim\Views\Twig;
use Twig\Error\Error as TwigError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Loader\ArrayLoader;
use Twig\Loader\ChainLoader;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityPolicy;

/**
 * Lets admins edit the Kwitansi (receipt) PDF template from the UI while the
 * template files under module/Report/View/kwitansi/ stay the untouched default.
 *
 * How it works
 * ------------
 * - The kwitansi is assembled from partials (header, program, terima, ...).
 *   Each partial is an editable "section" (see SECTIONS).
 * - An edited section is stored in the `report_template` table. A section that
 *   has no row there (or whose text equals the default) simply keeps using the
 *   file, so the default template is always what you get until somebody saves a
 *   change, and "reset to default" is just deleting the row.
 * - At render time the overrides are made visible to Twig through an ArrayLoader
 *   placed in front of the filesystem loader, under the same template names the
 *   layout already includes (see withOverrides()). kwitansi.twig needs no change.
 *
 * Security
 * --------
 * An override is code that runs on the server, so it is ALWAYS executed inside a
 * Twig sandbox with a small allow-list (see policy()): only if/for/set, a handful
 * of formatting filters, no include/extends/macro and no method calls. Edited
 * sections are also never handed an object: Twig probes `isset($obj->name)`
 * before the sandbox checks the property, and on an Eloquent model that probe
 * can lazy-load relations. So everything they see is plain arrays/strings (see
 * InvoiceController::getKwitansiData); toPdf() injects `report_info` as a model,
 * so the sandboxed wrapper swaps it for the array copy `report_info_data`.
 *
 * The overrides are intentionally read/written with the query builder instead of
 * an Eloquent model under module/*\/Model/: any model there is automatically
 * exposed by the generic REST API (/api/{model}s ...), and raw template text must
 * only be writable through KwitansiTemplateController, which lints it first.
 */
class KwitansiTemplate
{
    const REPORT = 'kwitansi';
    const TABLE = 'report_template';
    const VIEW = 'Report/View/kwitansi/kwitansi.twig';
    /** Same page, but arranged from the visual layout settings (see normalizeLayout()). */
    const VIEW_LAYOUT = 'Report/View/kwitansi/layout.twig';
    /** report_template.bagian that holds the visual layout JSON (not a section). */
    const LAYOUT_ROW = 'layout';
    const MAX_LENGTH = 100000;

    /**
     * key => label / description / file in module/Report/View/kwitansi/.
     * The key is what is stored in report_template.bagian, so don't rename it.
     */
    const SECTIONS = [
        'style' => [
            'label' => 'Gaya (CSS)',
            'file' => 'style.twig',
            'keterangan' => 'Ukuran huruf, jarak, lebar kolom, dan tampilan umum kwitansi. Ubah di sini jika isi kwitansi melebihi satu halaman.',
        ],
        'header' => [
            'label' => 'Header',
            'file' => 'header.twig',
            'keterangan' => 'Logo, alamat, judul, nomor dan tanggal kwitansi.',
        ],
        'program' => [
            'label' => 'Program & Pembayaran',
            'file' => 'program.twig',
            'keterangan' => 'Daftar centang Program Belajar (mengikuti master Program Belajar, jumlahnya dinamis) dan Jenis Pembayaran.',
        ],
        'terima' => [
            'label' => 'Terima Dari',
            'file' => 'terima.twig',
            'keterangan' => 'Sudah Terima Dari, Uang Sejumlah, dan Untuk Pembayaran.',
        ],
        'nominal' => [
            'label' => 'Nominal & Stempel',
            'file' => 'nominal.twig',
            'keterangan' => 'Kotak nominal (Rp) dan stempel.',
        ],
        'rekening' => [
            'label' => 'Rekening',
            'file' => 'rekening.twig',
            'keterangan' => 'Nomor rekening, nama penerima, dan ucapan terima kasih.',
        ],
        'keterangan' => [
            'label' => 'Keterangan',
            'file' => 'keterangan.twig',
            'keterangan' => 'Kolom Keterangan di sisi kanan.',
        ],
    ];

    /** Left-column blocks the visual editor can reorder, in the default order. */
    const BLOCKS = [
        'program' => 'Program & Pembayaran',
        'terima' => 'Terima Dari',
        'nominal' => 'Nominal & Stempel',
        'rekening' => 'Rekening',
    ];

    /** [min, max] for every number of the visual layout; the editor uses the same limits. */
    const LAYOUT_LIMITS = [
        'lebar_kiri' => [60, 90],
        'per_baris' => [1, 12],
        'ukuran_huruf' => [9, 16],
        'halaman' => [0, 20],
    ];

    /** What an edited section may use. Anything else is rejected by the sandbox. */
    const ALLOWED_TAGS = ['if', 'for', 'set'];
    const ALLOWED_FILTERS = [
        'escape', 'e', 'number_format', 'date', 'isMandarin', 'default', 'length', 'upper', 'lower',
        'capitalize', 'title', 'trim', 'nl2br', 'join', 'first', 'last', 'slice', 'batch', 'round', 'abs',
    ];
    const ALLOWED_FUNCTIONS = ['max', 'min'];

    // ------------------------------------------------------------------
    // Section definitions & sources
    // ------------------------------------------------------------------

    public static function sections()
    {
        return self::SECTIONS;
    }

    public static function isSection($key)
    {
        return is_string($key) && isset(self::SECTIONS[$key]);
    }

    /** The name the kwitansi layout uses to include this section. */
    public static function templateName($key)
    {
        return '/Report/View/kwitansi/' . self::SECTIONS[$key]['file'];
    }

    /** Where the edited text is registered; only reachable through the sandboxed include. */
    public static function customName($key)
    {
        return '__kwitansi_custom__/' . $key;
    }

    /** LF line endings and no BOM, so text from a browser compares equal to the files. */
    public static function normalize($source)
    {
        $source = (string) $source;

        if (substr($source, 0, 3) === "\xEF\xBB\xBF")
        {
            $source = substr($source, 3);
        }

        return str_replace(["\r\n", "\r"], "\n", $source);
    }

    public static function defaultSource($key)
    {
        $path = dirname(__DIR__) . '/View/kwitansi/' . self::SECTIONS[$key]['file'];

        return self::normalize(file_get_contents($path));
    }

    /** Trailing whitespace is ignored so an untouched editor never counts as a change. */
    public static function sameAsDefault($key, $source)
    {
        return rtrim(self::normalize($source)) === rtrim(self::defaultSource($key));
    }

    /** Ready-made starting points the editor can offer, keyed by section. */
    public static function examples()
    {
        $dir = dirname(__DIR__) . '/View/kwitansi/examples/';
        $file = $dir . 'program_beberapa_baris.twig';
        $examples = [];

        if (is_file($file))
        {
            $examples['program'][] = [
                'label' => 'Program belajar dibagi ke beberapa baris',
                'keterangan' => 'Cocok jika program belajar banyak. Ubah angka per_baris di baris pertama sesuai kebutuhan.',
                'konten' => self::normalize(file_get_contents($file)),
            ];
        }

        return $examples;
    }

    // ------------------------------------------------------------------
    // Stored overrides (report_template)
    // ------------------------------------------------------------------

    /** False until the report_template table exists (Fix Data patch 2/6). */
    public static function isInstalled()
    {
        try
        {
            return DB::schema()->hasTable(self::TABLE);
        }
        catch (\Exception $e)
        {
            return false;
        }
    }

    /**
     * [section key => text] for every section an admin has changed.
     * Never throws: before the table exists (patch 2/6 not run yet) this simply
     * returns nothing, so printing keeps using the default template.
     */
    public static function loadOverrides()
    {
        try
        {
            $rows = DB::table(self::TABLE)->where('report', self::REPORT)->get();
        }
        catch (\Exception $e)
        {
            return [];
        }

        $overrides = [];
        foreach ($rows as $row)
        {
            if (self::isSection($row->bagian))
            {
                $overrides[$row->bagian] = (string) $row->konten;
            }
        }

        return $overrides;
    }

    /**
     * Stores the given sections (and optionally the visual layout) in one
     * transaction. Anything equal to the default is deleted instead of stored,
     * which is how "reset" works.
     *
     * @param array $sources [section key => text]; callers must lint() first.
     * @param array|null $layout visual layout to store; null leaves it untouched
     */
    public static function saveOverrides(array $sources, $userId = null, $layout = null)
    {
        DB::connection()->transaction(function () use ($sources, $userId, $layout) {
            foreach ($sources as $key => $source)
            {
                if (!self::isSection($key))
                {
                    continue;
                }

                if (self::sameAsDefault($key, $source))
                {
                    self::deleteRow($key);
                    continue;
                }

                self::upsertRow($key, self::normalize($source), $userId);
            }

            if ($layout !== null)
            {
                $layout = self::normalizeLayout($layout);

                if (self::isDefaultLayout($layout))
                {
                    self::deleteRow(self::LAYOUT_ROW);
                }
                else
                {
                    self::upsertRow(self::LAYOUT_ROW, json_encode($layout), $userId);
                }
            }
        });
    }

    private static function deleteRow($bagian)
    {
        DB::table(self::TABLE)->where('report', self::REPORT)->where('bagian', $bagian)->delete();
    }

    private static function upsertRow($bagian, $konten, $userId)
    {
        $values = [
            'konten' => $konten,
            'user_id' => $userId ? $userId : null,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $exists = DB::table(self::TABLE)->where('report', self::REPORT)->where('bagian', $bagian)->exists();

        if ($exists)
        {
            DB::table(self::TABLE)->where('report', self::REPORT)->where('bagian', $bagian)->update($values);
        }
        else
        {
            DB::table(self::TABLE)->insert(array_merge(['report' => self::REPORT, 'bagian' => $bagian], $values));
        }
    }

    // ------------------------------------------------------------------
    // Visual layout (drag & drop editor)
    //
    // Unlike sections, the layout is NOT template code: it is a small set of
    // validated numbers/flags that the trusted file layout.twig turns into the
    // page, so it needs no sandbox. Whatever reaches the template has been
    // through normalizeLayout().
    // ------------------------------------------------------------------

    public static function defaultLayout()
    {
        return [
            'urutan' => array_keys(self::BLOCKS),
            'lebar_kiri' => 80,
            'keterangan' => true,
            'program' => ['susun' => false, 'per_baris' => 5, 'ukuran_huruf' => 12, 'mandarin' => true],
            'halaman' => ['vertikal' => 10, 'horizontal' => 10],
        ];
    }

    private static function intIn($value, $limits, $fallback)
    {
        if (!is_numeric($value))
        {
            return $fallback;
        }

        return (int) max($limits[0], min($limits[1], round($value)));
    }

    private static function boolIn($value, $fallback)
    {
        return is_bool($value) ? $value : $fallback;
    }

    /**
     * Any input in, a complete valid layout out: unknown keys dropped, numbers
     * clamped to LAYOUT_LIMITS, block order made a permutation of BLOCKS.
     */
    public static function normalizeLayout($input)
    {
        $layout = self::defaultLayout();

        if (!is_array($input))
        {
            return $layout;
        }

        if (isset($input['urutan']) && is_array($input['urutan']))
        {
            $order = [];
            foreach ($input['urutan'] as $key)
            {
                if (is_string($key) && isset(self::BLOCKS[$key]) && !in_array($key, $order, true))
                {
                    $order[] = $key;
                }
            }
            foreach (array_keys(self::BLOCKS) as $key)
            {
                if (!in_array($key, $order, true))
                {
                    $order[] = $key;
                }
            }
            $layout['urutan'] = $order;
        }

        if (array_key_exists('lebar_kiri', $input))
        {
            $layout['lebar_kiri'] = self::intIn($input['lebar_kiri'], self::LAYOUT_LIMITS['lebar_kiri'], $layout['lebar_kiri']);
        }
        if (array_key_exists('keterangan', $input))
        {
            $layout['keterangan'] = self::boolIn($input['keterangan'], $layout['keterangan']);
        }

        if (isset($input['program']) && is_array($input['program']))
        {
            $p = $input['program'];
            $layout['program']['susun'] = array_key_exists('susun', $p) ? self::boolIn($p['susun'], false) : false;
            $layout['program']['mandarin'] = array_key_exists('mandarin', $p) ? self::boolIn($p['mandarin'], true) : true;
            if (array_key_exists('per_baris', $p))
            {
                $layout['program']['per_baris'] = self::intIn($p['per_baris'], self::LAYOUT_LIMITS['per_baris'], 5);
            }
            if (array_key_exists('ukuran_huruf', $p))
            {
                $layout['program']['ukuran_huruf'] = self::intIn($p['ukuran_huruf'], self::LAYOUT_LIMITS['ukuran_huruf'], 12);
            }
        }

        if (isset($input['halaman']) && is_array($input['halaman']))
        {
            foreach (['vertikal', 'horizontal'] as $side)
            {
                if (array_key_exists($side, $input['halaman']))
                {
                    $layout['halaman'][$side] = self::intIn($input['halaman'][$side], self::LAYOUT_LIMITS['halaman'], 10);
                }
            }
        }

        return $layout;
    }

    public static function isDefaultLayout($layout)
    {
        return self::normalizeLayout($layout) === self::defaultLayout();
    }

    /**
     * The saved layout, or null when none is saved / it equals the default, in
     * which case the original kwitansi.twig is used. Never throws.
     */
    public static function loadLayout()
    {
        try
        {
            $row = DB::table(self::TABLE)->where('report', self::REPORT)->where('bagian', self::LAYOUT_ROW)->first();
        }
        catch (\Exception $e)
        {
            return null;
        }

        if (!$row)
        {
            return null;
        }

        $layout = self::normalizeLayout(json_decode($row->konten, true));

        return self::isDefaultLayout($layout) ? null : $layout;
    }

    // ------------------------------------------------------------------
    // Rendering with overrides (sandboxed)
    // ------------------------------------------------------------------

    public static function policy()
    {
        return new SecurityPolicy(
            self::ALLOWED_TAGS,
            self::ALLOWED_FILTERS,
            [], // no method calls at all
            [], // no property access on objects (none are provided)
            self::ALLOWED_FUNCTIONS
        );
    }

    /**
     * Registers the sandbox extension (globally OFF: only the includes made by
     * withOverrides() are sandboxed, every other report renders as before).
     * Twig refuses new extensions once it has rendered something; in that case
     * we cannot sandbox and must not apply overrides, hence the boolean.
     */
    public static function prepare(Twig $twig)
    {
        $env = $twig->getEnvironment();

        if ($env->hasExtension(SandboxExtension::class))
        {
            return true;
        }

        try
        {
            $env->addExtension(new SandboxExtension(self::policy(), false));
        }
        catch (\LogicException $e)
        {
            return false;
        }

        return true;
    }

    /**
     * Runs $callback while the given sections are served from $overrides instead
     * of the files, and always restores the original loader afterwards.
     *
     * Each section is registered twice: under the name the layout includes (a
     * tiny wrapper that includes the real text with sandboxed = true, handing it
     * the array copy of report_info instead of the model) and under a private
     * name holding the edited text.
     *
     * @throws TwigError when the overrides cannot be applied safely
     */
    public static function withOverrides(Twig $twig, array $overrides, callable $callback)
    {
        if (empty($overrides))
        {
            return $callback();
        }

        if (!self::prepare($twig))
        {
            throw new RuntimeError('Template kwitansi kustom tidak dapat diterapkan dengan aman.');
        }

        $templates = [];
        foreach ($overrides as $key => $source)
        {
            if (!self::isSection($key))
            {
                continue;
            }

            $templates[self::templateName($key)] =
                "{{ include('" . self::customName($key) . "', {report_info: report_info_data}, sandboxed = true) }}";
            $templates[self::customName($key)] = self::normalize($source);
        }

        $env = $twig->getEnvironment();
        $original = $env->getLoader();
        $env->setLoader(new ChainLoader([new ArrayLoader($templates), $original]));

        try
        {
            return $callback();
        }
        finally
        {
            $env->setLoader($original);
        }
    }

    // ------------------------------------------------------------------
    // Validation
    // ------------------------------------------------------------------

    /**
     * Renders one section (HTML only, no PDF) with sample data inside the
     * sandbox, which surfaces syntax errors, disallowed tags/filters/functions
     * and runtime errors in milliseconds.
     *
     * @return string|null a message for the admin, or null when the text is fine
     */
    public static function lint(Twig $twig, $key, $source, array $context)
    {
        $source = self::normalize($source);

        if (strlen($source) > self::MAX_LENGTH)
        {
            return 'Terlalu panjang (maksimal ' . number_format(self::MAX_LENGTH, 0, ',', '.') . ' karakter).';
        }

        try
        {
            self::withOverrides($twig, [$key => $source], function () use ($twig, $key, $context) {
                $twig->fetch(self::templateName($key), $context);
            });
        }
        catch (TwigError $e)
        {
            return self::describeError($e);
        }

        return null;
    }

    public static function describeError(TwigError $e)
    {
        $message = $e->getRawMessage();

        if ($e instanceof SecurityError)
        {
            $message = 'Tidak diizinkan: ' . $message;
        }

        $line = $e->getTemplateLine();

        return $line > 0 ? 'Baris ' . $line . ': ' . $message : $message;
    }

    // ------------------------------------------------------------------
    // Canvas (editor preview shown in the browser)
    // ------------------------------------------------------------------

    /** The A4 landscape page in CSS pixels (297 x 210 mm at 96 dpi), as dompdf lays it out. */
    const CANVAS_PAGE = ['w' => 1122.52, 'h' => 793.7];

    /**
     * Turns the kwitansi HTML into the document the editor shows in a sandboxed iframe.
     *
     * The HTML contains admin-edited markup, which may include <script>, remote images and so
     * on. So the very first thing in the document is a Content-Security-Policy that allows no
     * network access, only data: images/fonts, inline styles, and ONE script: our own editor
     * script (canvas.js), identified by a random nonce. A meta CSP only covers what comes after
     * it, hence "very first". The iframe also has no allow-same-origin (opaque origin).
     */
    public static function canvasDocument($html)
    {
        $dir = dirname(__DIR__) . '/View/kwitansi/';
        $nonce = bin2hex(random_bytes(16));

        $labels = ['header' => self::SECTIONS['header']['label'], 'keterangan' => self::SECTIONS['keterangan']['label']] + self::BLOCKS;
        $css = strtr(file_get_contents($dir . 'canvas.css'), [
            '__PAGE_W__' => self::CANVAS_PAGE['w'],
            '__PAGE_H__' => self::CANVAS_PAGE['h'],
        ]);
        $config = json_encode([
            'labels' => $labels,
            'draggable' => array_keys(self::BLOCKS),
            'limits' => ['lebar_kiri' => self::LAYOUT_LIMITS['lebar_kiri']],
            'page' => self::CANVAS_PAGE,
            'css' => $css,
        ], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $policy = "default-src 'none'; img-src data:; style-src 'unsafe-inline'; font-src data:; "
            . "script-src 'nonce-" . $nonce . "'; base-uri 'none'; form-action 'none'";

        // The logo comes as "data:image/png;base64, ..." (with a space), which dompdf accepts inside
        // an unquoted CSS url() but browsers do not: without this the logo is missing on the canvas.
        $html = preg_replace('/(url\(\s*data:[^,)\s]*,)\s+/i', '$1', $html);

        return '<!DOCTYPE html><meta http-equiv="Content-Security-Policy" content="' . $policy . '">'
            . '<script nonce="' . $nonce . '">var KW=' . $config . ';' . file_get_contents($dir . 'canvas.js') . '</script>'
            . $html;
    }

    // ------------------------------------------------------------------
    // Data shared by the real receipt, the preview and lint
    // ------------------------------------------------------------------

    /** Picks $keys from $attributes so templates only ever see a documented set. */
    public static function only(array $attributes, array $keys)
    {
        return array_intersect_key($attributes, array_flip($keys));
    }

    const TAGIHAN_KEYS = [
        'id', 'code', 'sub_total', 'potongan', 'total', 'hutang', 'status', 'tanggal', 'tanggal_lunas',
        'program_belajar', 'jenis_pembayaran', 'terima_dari', 'untuk_pembayaran', 'keterangan',
    ];
    const KURSUS_KEYS = ['id', 'kode', 'nama', 'no_rek', 'nama_rek'];
    const PROGRAM_KEYS = ['id', 'kode', 'nama', 'nama_mandarin'];

    /**
     * Stand-in data for the preview (when no real tagihan is chosen) and for lint.
     * Images are left empty here; the preview controller fills in real ones.
     * Keep the keys in sync with InvoiceController::getKwitansiData().
     */
    public static function sampleContext(array $programs, $status = 'l')
    {
        $lunas = $status === 'l';

        return [
            'tagihan' => [
                'id' => 0,
                'code' => 'KW/2026/0001',
                'sub_total' => 1500000,
                'potongan' => 0,
                'total' => 1500000,
                'hutang' => 0,
                'status' => $lunas ? 'l' : 'p',
                'tanggal' => '2026-01-05',
                'tanggal_lunas' => $lunas ? '2026-01-05' : null,
                'program_belajar' => empty($programs) ? '' : $programs[0]['nama'],
                'jenis_pembayaran' => 'Transfer',
                'terima_dari' => 'Nama Orang Tua / Siswa',
                'untuk_pembayaran' => 'Iuran Bulan Januari 2026',
                'keterangan' => 'Contoh keterangan kwitansi',
            ],
            'kursus' => [
                'id' => 0,
                'kode' => 'CABANG',
                'nama' => 'Cabang Contoh',
                'no_rek' => '1234567890',
                'nama_rek' => 'Nama Penerima',
            ],
            'siswa' => 'Nama Siswa',
            'nomor' => 'KW/2026/0001',
            'untuk' => '',
            'untuk_spp' => '',
            'terbilang' => 'Satu Juta Lima Ratus Ribu Rupiah',
            'program_belajars' => $programs,
            'program_belajar_is_other' => false,
            'report_info_data' => [
                'alamat' => "Jl. Contoh No. 1\nKota Contoh",
                'no_hp' => '0812-0000-0000',
                'email' => 'info@contoh.test',
            ],
            'logo' => '',
            'logo_bank' => '',
            'stamp_img' => '',
            'smileimage' => '',
        ];
    }

    /**
     * Documentation + insert snippets for the variable palette next to the code
     * editor ('sisip' is what a click/drag puts into the text). Keep in sync
     * with sampleContext().
     */
    public static function variables()
    {
        $v = function ($nama, $keterangan, $sisip = null) {
            return ['nama' => $nama, 'keterangan' => $keterangan, 'sisip' => $sisip === null ? '{{ ' . $nama . ' }}' : $sisip];
        };

        return [
            $v('nomor', 'Nomor kwitansi (kode tagihan)'),
            $v('siswa', 'Nama siswa'),
            $v('terbilang', 'Total dalam huruf'),
            $v('tagihan.total', 'Total dibayar (angka)', "{{ tagihan.total|number_format(0, ',', '.') }}"),
            $v('tagihan.status', '"l" berarti lunas', "{% if tagihan.status == 'l' %}Lunas{% else %}Belum lunas{% endif %}"),
            $v('tagihan.tanggal', 'Tanggal tagihan', "{{ tagihan.tanggal|date('d/m/Y') }}"),
            $v('tagihan.tanggal_lunas', 'Tanggal pelunasan', "{{ tagihan.tanggal_lunas|date('d/m/Y') }}"),
            $v('tagihan.program_belajar', 'Program belajar yang dipilih pada tagihan'),
            $v('tagihan.jenis_pembayaran', 'Tunai / Transfer / Cek / Bilyet Giro'),
            $v('tagihan.terima_dari', 'Sudah terima dari'),
            $v('tagihan.untuk_pembayaran', 'Untuk pembayaran'),
            $v('tagihan.keterangan', 'Keterangan'),
            $v('program_belajars', 'Daftar program belajar dari master (nama, nama_mandarin, kode); jumlahnya dinamis',
                "{% for program in program_belajars %}\n    {{ program.nama }}\n{% endfor %}"),
            $v('program_belajar_is_other', 'true jika program pada tagihan tidak ada di master',
                "{% if program_belajar_is_other %}{{ tagihan.program_belajar }}{% endif %}"),
            $v('kursus.nama', 'Nama cabang/kursus'),
            $v('kursus.no_rek', 'Nomor rekening'),
            $v('kursus.nama_rek', 'Nama penerima rekening'),
            $v('report_info.alamat', 'Alamat (Report Profile)'),
            $v('report_info.no_hp', 'No. HP (Report Profile)'),
            $v('report_info.email', 'Email (Report Profile)'),
            $v('logo', 'Logo (data URI)', '<img src="{{ logo }}" style="height: 60px">'),
            $v('logo_bank', 'Logo bank (data URI)', '<img src="{{ logo_bank }}" style="height: 30px">'),
            $v('stamp_img', 'Gambar stempel (data URI)', '<img src="{{ stamp_img }}" style="height: 70px">'),
            $v('smileimage', 'Gambar smile (data URI)', '<img src="{{ smileimage }}" style="height: 40px">'),
        ];
    }
}
