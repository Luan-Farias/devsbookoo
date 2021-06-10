<?php

require_once './config.php';

$_SESSION['token'] = null;

header('Location: '. $base);
exit;
