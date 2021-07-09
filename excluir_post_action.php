<?php

require_once './config.php';
require_once './models/Auth.php';
require_once './dao/PostDaoPostgres.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();

$idPost = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($idPost)
{
    $postDao = new PostDaoPostgres($pdo);

    $post = $postDao->delete($idPost, $userInfo->getId());
}
header('Location: ' . $base);
exit;