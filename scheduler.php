<?php


require_once 'vendor/autoload.php';

// load configuration
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// load container
$container = require "dependencies.php";
$container->get('Illuminate\Database\Capsule\Manager');
$modelList = $container->get("Model");

// main code
$tanggal = date("Y-m-01");
$siswas = $modelList->getModel('Siswa')->where('status', 'a')->get();

foreach ($siswas as $siswa)
{
    try
    {
        $siswa->triggerIuran(false, $tanggal);
    }
    catch(\Error $e) 
    {
        continue;
    }
}

echo "Berhasil";