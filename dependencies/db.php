<?php

use Psr\Container\ContainerInterface;

return [
    'Illuminate\Database\Capsule\Manager' => function (ContainerInterface $container) {
        $dbconfig = require __DIR__ . '/../config/database.php';
        $capsule = new \Illuminate\Database\Capsule\Manager;
        $capsule->addConnection([
            'driver' => 'mysql',
            'host' => $dbconfig['host'],
            'database' => $dbconfig['name'],
            'username' => $dbconfig['username'],
            'password' => $dbconfig['password'],
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        return $capsule;
    }
];