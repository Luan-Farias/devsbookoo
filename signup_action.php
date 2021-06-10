<?php

require_once './config.php';
require_once './models/Auth.php';

$name = filter_input(INPUT_POST, 'name');
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = filter_input(INPUT_POST, 'password');
$birthdate = filter_input(INPUT_POST, 'birthdate'); // dd/mm/yyyy

if (!($email && $password && $password && $birthdate))
{
    $_SESSION['flash'] = 'Campos não enviados';
    header('Location: ' . $base . "/signup.php");
    exit;
}

$auth = new Auth($pdo, $base);

$birthdate = explode('/', $birthdate);

if(count($birthdate) !== 3)
{
    $_SESSION['flash'] = 'Data Inválida';
    header('Location: ' . $base . "/signup.php");
    exit;
}

$birthdate = $birthdate[2] . '-' . $birthdate[1] . '-' . $birthdate[0]; 

if (strtotime($birthdate) === false)
{
    $_SESSION['flash'] = 'Data Inválida';
    header('Location: ' . $base . "/signup.php");
    exit;
}

if ($auth->emailExists($email))
{
    $_SESSION['flash'] = 'Email já cadastrado';
    header('Location: ' . $base . "/signup.php");
    exit;
}

$auth->registerUser($name, $email, $password, $birthdate);

header('Location: ' . $base);
exit;

