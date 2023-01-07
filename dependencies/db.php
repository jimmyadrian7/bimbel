<?php

use Psr\Container\ContainerInterface;

return [
    'Illuminate\Database\Capsule\Manager' => function (ContainerInterface $container) {
        $capsule = new \Illuminate\Database\Capsule\Manager;
        $capsule->addConnection([
            'driver' => 'mysql',
            'host' => $_ENV['db_host'],
            'database' => $_ENV['db_name'],
            'username' => $_ENV['db_user'],
            'password' => $_ENV['db_pass'],
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        return $capsule;
    }
];