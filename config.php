<?php

session_start();

$base = 'http://localhost/devsbookoo';

$db_name = 'postgres';
$db_host = '127.0.0.1';
$db_user = 'postgres';
$db_password = 'admin';

$pdo = new PDO('pgsql:dbname=' . $db_name . ";host=" . $db_host, $db_user, $db_password);
