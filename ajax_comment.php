<?php

require_once './config.php';
require_once './models/Auth.php';
require_once './models/PostComment.php';
require_once './dao/PostCommentDaoPostgres.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();

$body = filter_input(INPUT_POST, 'txt', FILTER_SANITIZE_STRING);
$idPost = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

$response = [];

if ($idPost && $body)
{
    $postDao = new PostCommentDaoPostgres($pdo);

    $newPostComment = new PostComment();

    $newPostComment->setIdUser($userInfo->getId());
    $newPostComment->setIdPost($idPost);
    $newPostComment->setBody($body);
    $newPostComment->setCreatedAt(date('Y-m-d H:i:s'));

    $postDao->insert($newPostComment);

    $response['link'] = $base . '/perfil.php?id=' . $userInfo->getId();
    $response['avatar'] = $base . '/media/avatars/' . $userInfo->getAvatar();
    $response['name'] = $userInfo->getName();
    $response['body'] = $newPostComment->getBody();
    $response['error'] = ''; 
}

header('Content-Type: application/json');
echo json_encode($response);
exit;
