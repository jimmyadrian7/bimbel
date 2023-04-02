<?php

$routes = [];
$modules_path = __DIR__ . "/../module";
$modules = scandir($modules_path);

foreach ($modules as $key => $module) {

    if ('.' == $module) continue;
    if ('..' == $module) continue;

    $is_dir = is_dir($modules_path . "/" . $module);

    if ($is_dir) {
        array_push($routes, $module);
    }
}

return $routes;