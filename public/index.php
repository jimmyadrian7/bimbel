<?php

use Slim\App;

// Bootstrap the app environment.
chdir(dirname(__DIR__));
require 'bootstrap.php';

// set .env
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Configure the Slim app.
$container = require "dependencies.php";

// Create App instance
$app = $container->get(App::class);

// Register routes.
require 'routes.php';

// Register middleware
(require 'middlewares.php')($app);


// Run the application!
$app->run();