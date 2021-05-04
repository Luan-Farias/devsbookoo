<?php

require_once 'dao/UserDaoPostgres.php';

class Auth {
    public function __construct(
        private Pdo $pdo,
        private string $base
    )
    {
    }

    public function checkToken(): User {
        if (!empty($_SESSION['token'])) {
            $token = $_SESSION['token'];

            $userDao = new UserDaoPostgres($this->pdo);
            $user = $userDao->findByToken($token);
            if ($user) {
                return $user;
            }
        }

        header('Location: ' . $this->base . "/login.php");
        exit;
    }
}