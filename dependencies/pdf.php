<?php

use Dompdf\Dompdf;
use Dompdf\Options;

return [
    'pdf' => function () {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('DOMPDF_UNICODE_ENABLED', true);
        // $options->set('allow_url_fopen', true);
        // $options->set('isFontSubsettingEnabled', true);
        $options->set('DOMPDF_ENABLE_FONTS_SUBSETTING', true);

        $pdf = new Dompdf();
        $fm = $pdf->getFontMetrics();
        $dir = __DIR__ . "/../module/Report/View/font/";

        $fm->registerFont(['family' => 'NotoSerifSC', 'style' => 'normal', 'weight' => 'normal'], $dir . 'NotoSerifSC-Regular.ttf');
        $fm->registerFont(['family' => 'NotoSerifSC', 'style' => 'normal', 'weight' => 'bold'],   $dir . 'NotoSerifSC-Bold.ttf');


        // $pdf->getFontMetrics()->registerFont(
        //     ['family' => 'SimSun', 'style' => 'normal', 'weight' => 'normal'],
        //     // '/module/Report/View/font/SimSun.ttf'
        //     __DIR__ . "/../module/Report/View/font/SimSun.ttf"
        // );

        // $pdf->getFontMetrics()->registerFont(
        //     ['family' => 'fireflysung', 'style' => 'normal', 'weight' => 'normal'],
        //     // '/module/Report/View/font/SimSun.ttf'
        //     __DIR__ . "/../module/Report/View/font/fireflysung.ttf"
        // );

        $pdf->setOptions($options);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf;
    }
];