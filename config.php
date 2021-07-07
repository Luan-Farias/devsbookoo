<?php

session_start();

$base = 'http://192.168.0.114/devsbookoo';

$db_name = 'devsbook';
$db_host = 'localhost';
$db_user = 'postgres';
$db_password = 'admin';

$pdo = new PDO('pgsql:dbname=' . $db_name . ";host=" . $db_host, $db_user, $db_password);
