<?php

use DI\ContainerBuilder;


// set container
$containerBuilder = new ContainerBuilder();

// Set up Dependencies
$containerBuilder->addDefinitions('./dependencies/app.php');
$containerBuilder->addDefinitions('./dependencies/BasePathMiddleware.php');
$containerBuilder->addDefinitions('./dependencies/db.php');
$containerBuilder->addDefinitions('./dependencies/ErrorMiddleware.php');
$containerBuilder->addDefinitions('./dependencies/ModelList.php');
$containerBuilder->addDefinitions('./dependencies/session.php');
$containerBuilder->addDefinitions('./dependencies/twig.php');
$containerBuilder->addDefinitions('./dependencies/pdf.php');
$containerBuilder->addDefinitions('./dependencies/excel.php');
$containerBuilder->addDefinitions('./dependencies/mailer.php');
$containerBuilder->addDefinitions('./dependencies/HttpRequest.php');
$containerBuilder->addDefinitions('./dependencies/ErrorHandler.php');


// Build PHP-DI Container instance
$container = $containerBuilder->build();

return $container;