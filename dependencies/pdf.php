<?php

use Dompdf\Dompdf;

return [
    'pdf' => function () {
        $pdf = new Dompdf();
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf;
    }
];