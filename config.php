<?php

$base = 'http://localhost/devsbookoo';

$db_name = 'devsbook';
$db_host = 'localhost';
$db_user = 'postgres';
$db_password = 'admin';

$pdo = new PDO('pgsql:dbname=' . $db_name . ";host=" . $db_host, $db_user, $db_password);
