<?php

use Psr\Container\ContainerInterface;

return [
    'Monolog\Logger' => function (ContainerInterface $container) {
        $settings = $container->get('settings')['settings']['logger'];
        $logger = new \Monolog\Logger($settings['name']);
        $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
        return $logger;
    }
];