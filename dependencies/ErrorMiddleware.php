<?php

use Psr\Container\ContainerInterface;
use Slim\Middleware\ErrorMiddleware;
use Slim\App;

return [
    ErrorMiddleware::class => function (ContainerInterface $container) {
        $app = $container->get(App::class);
        $settings = $container->get('settings');

        return new ErrorMiddleware(
            $app->getCallableResolver(),
            $app->getResponseFactory(),
            (bool)$settings['settings']['display_error_details'],
            (bool)$settings['settings']['log_errors'],
            (bool)$settings['settings']['log_error_details']
        );
    },

];
