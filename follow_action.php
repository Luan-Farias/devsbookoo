<?php

require_once './config.php';
require_once './models/Auth.php';
require_once './dao/UserDaoPostgres.php';
require_once './dao/UserRelationDaoPostgres.php';

function returnToProfile(string $base, int $id)
{
    header('Location: ' . $base . '/perfil.php?id=' . $id);
    exit;
}

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id)
    returnToProfile($base, $id);
if ($id === $userInfo->getId())
    returnToProfile($base, $id);

$userRelationDao = new UserRelationDaoPostgres($pdo);
$userDao = new UserDaoPostgres($pdo);

$user = $userDao->findById($id);
if (!$user)
    returnToProfile($base, $id);

$userRelation = new UserRelation();
$userRelation->setUserFrom($userInfo->getId());
$userRelation->setUserTo($user->getId());
if (!$userRelationDao->isFollowing($userInfo->getId(), $id))
    $userRelationDao->insert($userRelation);
else
    $userRelationDao->delete($userRelation);

returnToProfile($base, $id);
