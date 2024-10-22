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
        $pdf->setOptions($options);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf;
    }
];