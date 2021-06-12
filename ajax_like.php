<?php

require_once './config.php';
require_once './models/Auth.php';
require_once './dao/PostLikeDaoPostgres.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();

$idPost = filter_input(INPUT_GET, 'id');

if (!empty($idPost))
{
    $postLikeDao = new PostLikeDaoPostgres($pdo);
    $postLikeDao->likeToggle($idPost, $userInfo->getId());
}

exit;
