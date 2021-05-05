<?php

require './config.php';

$_SESSION['token'] = null;

header('Location: '. $base);
exit;
