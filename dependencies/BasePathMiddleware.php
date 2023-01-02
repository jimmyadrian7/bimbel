<?php

use Selective\BasePath\BasePathMiddleware;
use Psr\Container\ContainerInterface;
use Slim\App;

return [
    BasePathMiddleware::class => function (ContainerInterface $container) {
        return new BasePathMiddleware($container->get(App::class));
    },
];
