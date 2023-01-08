<?php

return [
    'excel' => function () {
        $writer = new XLSXWriter();
        return $writer;
    }
];