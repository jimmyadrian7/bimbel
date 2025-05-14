<?php

use Slim\Views\Twig;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MandarinDetectorExtension extends AbstractExtension {
    public function getFilters() {
        return [
            new TwigFilter('isMandarin', function ($text) {
                return preg_match('/\p{Han}/u', $text);
            })
        ];
    }
}


return [
    'twig' => function ($path) {
        $twig = Twig::create('module');

        // Add the custom extension
        $twig->addExtension(new MandarinDetectorExtension());
        
        return $twig;
    }
];