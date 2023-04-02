<?php

class Import {

    function __construct()
    {
        require_once 'vendor/autoload.php';

        $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();

        $this->container = require "dependencies.php";
        $this->container->get('Illuminate\Database\Capsule\Manager');

        $this->modelList = $this->container->get("Model");
    }

    function run()
    {
        chdir(dirname(__DIR__));
        
        $db_name = $_ENV['db_name'];
        $db_user = $_ENV['db_user'];

        // Drop if exist then create database
        echo "Drop if exist then create database.... \r\n";
        exec('mysql -u '.$db_user.' -e "DROP DATABASE IF EXISTS '.$db_name.'; create database '.$db_name.'"');
        
        // Run prepared script to create table and the relation
        echo "Run prepared script to create table and the relation.... \r\n";

        $sql_file = scandir(__DIR__ . '/sql');
        $extra = "";
        $cmd = 'mysql -u '.$db_user.' '.$db_name.' < ' . __DIR__ . '/sql/';

        foreach ($sql_file as $key => $file) {

            if ('.' == $file) continue;
            if ('..' == $file) continue;

            $ext = explode(".", $file);
            $ext = $ext[count($ext) - 1];

            if (str_contains($file, "_extra"))
            {
                $extra = $file;
                continue;
            }

            if ($ext == 'sql') {
                echo "file: " . $file . "\r\n";
                exec($cmd . $file);
            }
        }

        if (!empty($extra))
        {
            exec($cmd . $extra);
        }
    }
}