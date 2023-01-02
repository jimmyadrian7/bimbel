<?php
// Database.
$name = 'bimbel';
$username = 'root';
$password = '';
$host = 'localhost';

return [
    'type' => 'mysql',
    'options' => [
        'PDO::MYSQL_ATTR_INIT_COMMAND' => 'SET NAMES \'UTF8\''
    ],
    'dsn' => 'mysql:dbname=' . $name . ';host=' . $host,
    'host' => $host,
    'name' => $name,
    'username' => $username,
    'password' => $password,
];
