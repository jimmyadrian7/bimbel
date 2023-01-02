<?php

use Slim\Views\Twig;

return [
    'twig' => function ($path) {
        $twig = Twig::create('module');
        return $twig;
    }
];