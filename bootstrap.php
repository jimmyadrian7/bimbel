<?php
// Turn on debug.
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Include Composer autoloader.
require_once __DIR__ . '/vendor/autoload.php';

// set .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Start the session.
session_cache_limiter(false);
session_start();
