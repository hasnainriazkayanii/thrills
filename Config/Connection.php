<?php
   define('DB_SERVER', 'localhost');


//define('DB_USERNAME', 'root');
//
//define('DB_PASSWORD', '');
//
//define('DB_DATABASE', 'upwork');


   define('DB_USERNAME', 'homestead');

   define('DB_PASSWORD', 'secret');

   define('DB_DATABASE', 'ticket-app');


$dbDetails = array(
    'host' => DB_SERVER,
    'user' => DB_USERNAME,
    'pass' => DB_PASSWORD,
    'db'   => DB_DATABASE
);


$db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

error_reporting(E_ALL & ~E_NOTICE);


?>