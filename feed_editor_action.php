<?php

require './config.php';
require './models/Auth.php';
require './dao/PostDaoPostgres.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();

$body = filter_input(INPUT_POST, 'body', FILTER_SANITIZE_STRING);

if ($body)
{
    $postDao = new PostDaoPostgres($pdo);

    $newPost = new Post();

    $newPost->setIdUser($userInfo->getId());
    $newPost->setType('text');
    $newPost->setBody($body);
    $newPost->setCreatedAt(date('Y-m-d H:i:s'));

    $postDao->insert($newPost);
}

header('Location: ' . $base);
exit;
