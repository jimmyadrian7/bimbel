<?php
namespace Bimbel\Web\Controller;

use \Bimbel\Master\Controller\Controller;
use \Bimbel\Web\Model\KonfigurasiWeb;
use \Bimbel\Web\Model\Pengumuman;
use \Bimbel\Web\Model\Promo;
use \Bimbel\Web\Model\Testimoni;

class ViewController extends Controller
{
    public function homeView($response)
    {
        $data = [
            "base_url" => "/bimbel"
        ];

        $konfigurasi = new KonfigurasiWeb();
        $pengumuman = new Pengumuman();
        $promo = new Promo();
        $testimoni = new Testimoni();

        $data['konfigurasi'] = $konfigurasi->first();
        $data['pengumumans'] = $pengumuman->get()->chunk(2);
        $data['promos'] = $promo->get();
        $data['testimonis'] = $testimoni->get();

        $twig = $this->container->get("twig");
        $rendered = $twig->fetch("Web/View/index.twig", $data);

        return $rendered;
    }
}
