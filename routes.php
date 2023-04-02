<?php
// Import all routes from the config.
$modules = require './config/routes.php';
foreach ($modules as $module) {
    $file_path = './module/' . $module . '/index.php';

    if(file_exists($file_path))
    {
        require './module/' . $module . '/index.php';
    }
}
