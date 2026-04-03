<?php
namespace Bimbel\Report\Controller;

use \Bimbel\Core\Controller\Controller as CoreController;
use \Illuminate\Database\Capsule\Manager;
use Psr\Container\ContainerInterface;

class BaseReportController extends CoreController
{
    protected $container;
    protected $database;
    protected $path;

    public function __construct(
        Manager $database,
        ContainerInterface $container
    ) {
        $this->database = $database;
        $this->container = $container;
        $this->path = dirname(__DIR__) . "/View/images/";
    }

    public function getImage($filename)
    {
        $image = $this->path . $filename;
        $image = file_get_contents($image);
        $image = 'data:image/png;base64, ' . base64_encode($image);

        return $image;
    }

    public function getBackground($name = 'coba.jpg')
    {
        $background = $this->path . $name;
        $background = file_get_contents($background);
        $background = 'data:image/png;base64, ' . base64_encode($background);

        return $background;
    }

    public function convertDate($date, $format='d/m/Y')
    {
        $monthNames = [
            'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 'April' => 'April',
            'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli', 'August' => 'Agustus',
            'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
        ];
        $result = (new \Datetime($date))->format($format);
        $result = str_replace(array_keys($monthNames), array_values($monthNames), $result);

        return $result;
    }

    public function toPdf($view, $data, $background = 'coba.jpg', $orientation = 'landscape')
    {
        $pdf = $this->container->get("pdf");
        $twig = $this->container->get("twig");

        // $data['logo'] = $this->getImage('logo.png');
        // Get Report Info
        $report_info = \Bimbel\Master\Model\ReportInfo::find(1);
        $data['logo'] = 'data:image/png;base64, ' . $report_info->logo->base64;
        $data['report_info'] = $report_info;

        $data['background'] = $this->getBackground($background);
        $html = $twig->fetch($view, $data);

        $pdf->setPaper('A4', $orientation);

        $pdf->loadHtml($html);
        $pdf->render();
        $result = $pdf->output();
        $result = ["data" => base64_encode($result)];
        $result = json_encode($result);

        return $result;
    }
}