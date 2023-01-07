<?php

use Psr\Container\ContainerInterface;
use Slim\Middleware\ErrorMiddleware;
use Slim\App;

return [
    ErrorMiddleware::class => function (ContainerInterface $container) {
        $app = $container->get(App::class);

        return new ErrorMiddleware(
            $app->getCallableResolver(),
            $app->getResponseFactory(),
            (bool)$_ENV['display_error_details'],
            (bool)$_ENV['log_errors'],
            (bool)$_ENV['log_error_details']
        );
    },

];
