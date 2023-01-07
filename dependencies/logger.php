<?php

use Psr\Container\ContainerInterface;

return [
    'Monolog\Logger' => function (ContainerInterface $container) {
        $logger = new \Monolog\Logger($_ENV['log_name']);
        $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($_ENV['log_path'], \Monolog\Logger::DEBUG));
        return $logger;
    }
];