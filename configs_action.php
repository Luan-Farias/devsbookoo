<?php

require_once './config.php';
require_once './models/Auth.php';
require_once './dao/UserDaoPostgres.php';
require_once './functions/returnToConfigsPage.php';
require_once './functions/resizeImageAndSave.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();

$userDao = new UserDaoPostgres($pdo);

$name = filter_input(INPUT_POST, 'name');
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$birthdate = filter_input(INPUT_POST, 'birthdate');
$city = filter_input(INPUT_POST, 'city');
$work = filter_input(INPUT_POST, 'work');
$password = filter_input(INPUT_POST, 'password');
$passwordConfirmation = filter_input(INPUT_POST, 'password_confirmation');


if (empty($name) || empty($email))
{
    returnToConfigsPage($base, 'Nome e/ou E-mail não foram enviados');
}

$userInfo->setName($name);
$userInfo->setCity($city);
$userInfo->setWork($work);

if ($userInfo->getEmail() !== $email)
{
    $userWithNewEmail = $userDao->findByEmail($email);

    if ($userWithNewEmail)
        returnToConfigsPage($base, 'Email já está sendo utilizado por outro usuário');
    
    $userInfo->setEmail($email);
}

$birthdate = explode('/', $birthdate);
if (count($birthdate) !== 3) 
    returnToConfigsPage($base, 'Data de nascimento inválida!');
$birthdate = $birthdate[2] . '-' . $birthdate[1] . '-' . $birthdate[0];
if (!strtotime($birthdate))
    returnToConfigsPage($base, 'Data de nascimento inválida');
$userInfo->setBirthdate($birthdate);

if (!empty($password))
{
    if ($password !== $passwordConfirmation)
        returnToConfigsPage($base, 'As senhas não coincidem!');
    
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $userInfo->setPassword($passwordHash);
}

if (isset($_FILES['avatar']) && !empty($_FILES['avatar']['tmp_name']))
{
    $newAvatar = $_FILES['avatar'];
    $avatarFileName = resizeImageAndSave($newAvatar, './media/avatars', 400, 400);

    if ($avatarFileName)
        $userInfo->setAvatar($avatarFileName);
}

if (isset($_FILES['cover']) && !empty($_FILES['cover']['tmp_name']))
{
    $newCover = $_FILES['cover'];
    $coverFileName = resizeImageAndSave($newCover, './media/covers', 1100, 400);

    if ($coverFileName)
        $userInfo->setCover($coverFileName);
}

$userDao->update($userInfo);
returnToConfigsPage($base);
