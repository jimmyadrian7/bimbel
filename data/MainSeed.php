<?php

class MainSeed {

    function __construct()
    {
        require_once 'vendor/autoload.php';

        $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();

        $this->container = require "dependencies.php";
        $this->container->get('Illuminate\Database\Capsule\Manager');

        $this->modelList = $this->container->get("Model");
    }

    function extract_csv_data($file_name)
    {
        $data_file = fopen(__DIR__ . '/csv/'.$file_name, "r");
        $counter = 0;
        $data = [];
        $header = [];
        while (! feof($data_file)) {
            $row = fgetcsv($data_file);

            if ($counter == 0) {
                $header = $row;
            }else{
                if ($row) {
                    $col_data = [];
                    foreach ($row as $key => $col) {
                        $col_data[$header[$key]] = $col ? $col : NULL;
                    }
                    array_push($data, $col_data);
                }
            }
            $counter++;
        }
        fclose($data_file);

        return $data;
    }
    
    function importData($data_name)
    {
        $model_name = join("", array_map("ucfirst", explode("_", $data_name)));
        $object = $this->modelList->getModel($model_name);

        $data = $this->extract_csv_data($data_name.'.csv');

        if ($data_name != 'file')
        {
            $object->insert($data);
        }
        else
        {
            foreach ($data as $value)
            {
                $object = $this->modelList->getModel($model_name);
                $object->create($value);
            }
        }
    }

    function run()
    {
        chdir(dirname(__DIR__));
        
        $db_name = $_ENV['db_name'];

        // Drop if exist then create database
        echo "Drop if exist then create database.... \r\n";
        exec('../../bin/mysql -u root -e "DROP DATABASE IF EXISTS '.$db_name.'; create database '.$db_name.'"');
        
        // Run prepared script to create table and the relation
        echo "Run prepared script to create table and the relation.... \r\n";
        exec('../../bin/mysql -u root '.$db_name.' < ' . __DIR__ . '/Bimbel.sql');

        // import data
        echo "Import data from excel.... \r\n";
        $this->importData('agama');
        $this->importData('diskon');
        $this->importData('pembiayaan');
        $this->importData('sequance');
        $this->importData('menu');
        $this->importData('role');
        $this->importData('role_menu');
        $this->importData('konfigurasi_web');
        $this->importData('referal');

        // Sample Data
        echo "Import sample data.... \r\n";
        $this->importData('file');
        $this->importData('pengumuman');
        $this->importData('promo');
        $this->importData('testimoni');
        $this->importData('iuran');
        $this->importData('iuran_detail');

        // Create super admin user
        echo "Create super admin user.... \r\n";
        $guru = $this->modelList->getModel('Guru')->create(['orang' => ['nama' => 'Admin']]);
        $user = $this->modelList->getModel('User')->where(['orang_id' => $guru->orang_id])->first();
        $user->update(['username' => 'admin', 'pass' => 'admin', 'jenis_user' => 's']);
    }
}