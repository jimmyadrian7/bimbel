<?php
/**
 * Standalone check for the editable Kwitansi template (no DB, no PHPUnit needed):
 *
 *     php tests/Unit/Helper/kwitansi_template_check.php
 *
 * Verifies that the default partials pass the Twig sandbox, that dangerous
 * constructs are rejected, and that overrides apply without leaking into other
 * renders. Exit code is non-zero on failure.
 */
$root = dirname(__DIR__, 3);
chdir($root);
require $root . '/vendor/autoload.php';
error_reporting(E_ALL & ~E_DEPRECATED);
use Slim\Views\Twig; use Twig\Extension\AbstractExtension; use Twig\TwigFilter;
use Bimbel\Report\Helper\KwitansiTemplate as K;
if (!class_exists('MandarinDetectorExtension')) { class MandarinDetectorExtension extends AbstractExtension {
    public function getFilters() { return [ new TwigFilter('isMandarin', function ($t) { return preg_match('/\p{Han}/u', $t); }) ]; }
} }
function mk() { $t = Twig::create('module'); $t->addExtension(new MandarinDetectorExtension()); return $t; }
$programs = [['id'=>1,'kode'=>'A','nama'=>'Mandarin','nama_mandarin'=>'中文班'],['id'=>2,'kode'=>'B','nama'=>'Inggris','nama_mandarin'=>'英文班']];
$ctx = K::sampleContext($programs);
$fail = 0;
function check($name, $ok) { global $fail; echo ($ok ? "PASS" : "FAIL") . "  $name\n"; if (!$ok) $fail++; }

// 1. every default file must be valid inside the sandbox
foreach (K::sections() as $key => $def) {
    $err = K::lint(mk(), $key, K::defaultSource($key), $ctx);
    check("default '$key' passes sandbox" . ($err ? " [$err]" : ''), $err === null);
}
// 2. blocked payloads (put in 'terima' section)
$bad = [
  "map filter"      => "{{ ['id']|map('system') }}",
  "filter filter"   => "{{ ['id']|filter('system') }}",
  "raw filter"      => "{{ siswa|raw }}",
  "include tag"     => "{% include '/Master/Model/User.php' %}",
  "include func"    => "{{ include('/Master/Model/User.php') }}",
  "source func"     => "{{ source('/Master/Model/User.php') }}",
  "constant func"   => "{{ constant('PHP_VERSION') }}",
  "apply tag"       => "{% apply upper %}x{% endapply %}",
  "macro tag"       => "{% macro a() %}x{% endmacro %}",
  "sort filter"     => "{{ ['a']|sort('system') }}",
  "syntax error"    => "{% if %}",
  "unclosed"        => "{% for x in program_belajars %}",
];
foreach ($bad as $n => $src) { $e = K::lint(mk(), 'terima', $src, $ctx); check("blocked: $n" . ($e ? "  -> $e" : ''), $e !== null); }
// 3. allowed payloads
$good = [
  "set/if/for"   => "{% set n = 2 %}{% for p in program_belajars|batch(n) %}{% if loop.first %}{{ p|length }}{% endif %}{% endfor %}",
  "report_info"  => "{{ report_info.alamat }} {{ report_info.no_hp }} {{ report_info.email is empty ? 'x' : 'y' }}",
  "mandarin"     => "{{ tagihan.terima_dari|isMandarin ? 'a' : 'b' }}",
  "number_format"=> "{{ tagihan.total|number_format(0, ',', '.') }} {{ 'now'|date('d/m/Y') }}",
  "empty"        => "",
];
foreach ($good as $n => $src) { $e = K::lint(mk(), 'terima', $src, $ctx); check("allowed: $n" . ($e ? "  -> $e" : ''), $e === null); }

// 3b. no object reachable: model-ish access just yields nothing (and no DB touch)
$t = mk();
$o = K::withOverrides($t, ['terima'=>"[{{ report_info.logo }}|{{ report_info.delete }}|{{ report_info.alamat }}]"], function() use ($t,$ctx){ return $t->fetch(K::templateName('terima'), $ctx); });
check("report_info is array copy, no objects: $o", strpos($o, '[|||') === false && strpos($o, 'Jl. Contoh') !== false && strpos($o,'[||') !== false);
$o = K::withOverrides($t, ['terima'=>"[{{ attribute(report_info, 'delete') }}]"], function() use ($t,$ctx){ return $t->fetch(K::templateName('terima'), $ctx); });
check("attribute() is inert on arrays: $o", trim($o) === '[]');
// 4. override is applied, sandbox is off elsewhere, loader restored
$t = mk();
$out = K::withOverrides($t, ['nominal' => 'CUSTOM-{{ tagihan.status }}'], function () use ($t, $ctx) { return $t->fetch('Report/View/kwitansi/kwitansi.twig', $ctx); });
check("override rendered in full layout", strpos($out, 'CUSTOM-l') !== false);
check("other sections still default", strpos($out, 'Sudah Terima Dari') !== false);
$out2 = $t->fetch('Report/View/kwitansi/kwitansi.twig', $ctx);
check("loader restored (default again)", strpos($out2, 'CUSTOM-') === false && strpos($out2, 'Stamp Students') !== false);
// 5. fail-closed when extension can't be added after Twig initialised
$t = mk(); $t->fetch('Report/View/kwitansi/kwitansi.twig', $ctx);
try { K::withOverrides($t, ['nominal'=>'x'], function(){}); check("fail closed after init", false); } catch (\Twig\Error\Error $e) { check("fail closed after init", true); }
// 6. sameAsDefault ignores CRLF + trailing ws
check("sameAsDefault CRLF", K::sameAsDefault('header', str_replace("\n","\r\n",K::defaultSource('header'))."\n\n"));
check("differs when edited", !K::sameAsDefault('header', K::defaultSource('header')."x"));
// 7. visual layout validation: anything in, a complete valid layout out
$d = K::defaultLayout();
check("layout: non-array input -> default", K::normalizeLayout('x') === $d && K::normalizeLayout(null) === $d && K::normalizeLayout([]) === $d);
check("layout: default is recognised", K::isDefaultLayout($d) && K::isDefaultLayout([]));
$n = K::normalizeLayout(['urutan'=>['rekening','x','rekening','program',123,['a']], 'lebar_kiri'=>9999, 'keterangan'=>'no',
    'program'=>['susun'=>'yes','per_baris'=>-5,'ukuran_huruf'=>'abc','mandarin'=>0], 'halaman'=>['vertikal'=>-3,'horizontal'=>'7.6'], 'evil'=>'{{ x }}']);
check("layout: order is a permutation of the known blocks", $n['urutan'] === ['rekening','program','terima','nominal'], $n['urutan']);
check("layout: numbers clamped to limits", $n['lebar_kiri'] === 90 && $n['program']['per_baris'] === 1 && $n['halaman']['vertikal'] === 0 && $n['halaman']['horizontal'] === 8, $n);
check("layout: non-numeric falls back to default", $n['program']['ukuran_huruf'] === 12);
check("layout: non-bool flags fall back to default", $n['keterangan'] === true && $n['program']['susun'] === false && $n['program']['mandarin'] === true);
check("layout: unknown keys dropped", !isset($n['evil']) && array_keys($n) === array_keys($d));
check("layout: normalize is idempotent", K::normalizeLayout($n) === $n);
check("layout: real change is not default", !K::isDefaultLayout(['halaman'=>['vertikal'=>5]]) && !K::isDefaultLayout(['urutan'=>['nominal']]));
check("layout: every block key maps to a section file", !array_diff(array_keys(K::BLOCKS), array_keys(K::sections())));
foreach (K::variables() as $v) { if (!isset($v['sisip']) || $v['sisip'] === '') { check("variable '{$v['nama']}' has insert snippet", false); } }
foreach (K::variables() as $v) {
    $e = K::lint(mk(), 'terima', $v['sisip'], $ctx);
    check("palette snippet is valid in the sandbox: {$v['nama']}", $e === null, $e);
}
echo $fail ? "\n$fail FAILED\n" : "\nALL PASSED\n"; exit($fail ? 1 : 0);
