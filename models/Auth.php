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

    public function validateLogin(string $email, string $password): bool {
        $userDao = new UserDaoPostgres($this->pdo);

        $user = $userDao->findByEmail($email);

        if ($user) {
            if (password_verify($password, $user->getPassword())) {
                $token = md5(time() . rand(0, 9999));

                $user->setToken($token);
                $userDao->update($user);

                return true;
            }
        }

        return false;
    }
}