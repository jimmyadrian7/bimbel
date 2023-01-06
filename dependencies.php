<?php

use DI\ContainerBuilder;


// set container
$containerBuilder = new ContainerBuilder();

$base_url = [
    'base_url' => function () {
        $base_url = "/bimbel";
        // $path = explode("/", dirname(__FILE__));
        // $path = "/" . array_pop($path);

        return "/";
    }
];

// Set up Dependencies
$containerBuilder->addDefinitions('./dependencies/settings.php');
$containerBuilder->addDefinitions('./dependencies/app.php');
$containerBuilder->addDefinitions('./dependencies/BasePathMiddleware.php');
$containerBuilder->addDefinitions('./dependencies/db.php');
$containerBuilder->addDefinitions('./dependencies/ErrorMiddleware.php');
$containerBuilder->addDefinitions('./dependencies/logger.php');
$containerBuilder->addDefinitions('./dependencies/settings.php');
$containerBuilder->addDefinitions('./dependencies/ModelList.php');
$containerBuilder->addDefinitions('./dependencies/session.php');
$containerBuilder->addDefinitions('./dependencies/twig.php');
$containerBuilder->addDefinitions('./dependencies/pdf.php');
$containerBuilder->addDefinitions('./dependencies/mailer.php');
$containerBuilder->addDefinitions($base_url);


// Build PHP-DI Container instance
$container = $containerBuilder->build();

return $container;