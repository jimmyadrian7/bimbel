<?php
namespace Bimbel\Report\Controller;

use \Bimbel\Core\Controller\Controller as CoreController;
use \Illuminate\Database\Capsule\Manager;
use \Monolog\Logger;
use Psr\Container\ContainerInterface;

class BaseReportController extends CoreController
{
    protected $container;
    protected $database;
    protected $path;

    public function __construct(
        Manager $database,
        Logger $logger,
        ContainerInterface $container
    ) {
        $this->database = $database;
        $this->container = $container;
        $this->path = dirname(__DIR__) . "/View/images/";
    }

    public function getLogo()
    {
        $logo = $this->path . "logo.png";
        $logo = file_get_contents($logo);
        $logo = 'data:image/png;base64, ' . base64_encode($logo);

        return $logo;
    }

    public function getBackground($name = 'coba.jpg')
    {
        $background = $this->path . "coba.jpg";
        $background = file_get_contents($background);
        $background = 'data:image/png;base64, ' . base64_encode($background);

        return $background;
    }

    public function convertDate($date, $format='d/m/Y')
    {
        $result = (new \Datetime($date))->format($format);
        return $result;
    }

    public function toPdf($view, $data, $background = 'coba.jpg')
    {
        $pdf = $this->container->get("pdf");
        $twig = $this->container->get("twig");

        $data['logo'] = $this->getLogo();
        $data['background'] = $this->getBackground($background);
        $html = $twig->fetch($view, $data);

        $pdf->loadHtml($html);
        $pdf->render();
        $result = $pdf->output();
        $result = ["data" => base64_encode($result)];
        $result = json_encode($result);

        return $result;
    }
}