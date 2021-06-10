<?php

require_once 'dao/UserDaoPostgres.php';

class Auth {
    private UserDaoPostgres $userDao;
    public function __construct(
        private Pdo $pdo,
        private string $base
    )
    {
        $this->userDao = new UserDaoPostgres($pdo);
    }

    public function checkToken(): User
    {
        if (!empty($_SESSION['token']))
        {
            $token = $_SESSION['token'];

            $user = $this->userDao->findByToken($token);
            if ($user)
            {
                return $user;
            }
        }

        header('Location: ' . $this->base . "/login.php");
        exit;
    }

    public function validateLogin(string $email, string $password): bool {
        $user = $this->userDao->findByEmail($email);

        if ($user)
        {
            if (password_verify($password, $user->getPassword()))
            {
                $token = md5(time() . rand(0, 9999));

                $user->setToken($token);
                $this->userDao->update($user);

                $_SESSION['token'] = $token;

                return true;
            }
        }

        return false;
    }

    public function emailExists(string $email)
    {
        return $this->userDao->findByEmail($email) ? true : false;
    }

    public function registerUser(string $name, string $email, string $password, string $birthdate)
    {
        $newUser = new User();

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $token = md5(time() . rand(0, 9999));

        $newUser->setName($name);
        $newUser->setEmail($email);
        $newUser->setPassword($hash);
        $newUser->setBirthdate($birthdate);
        $newUser->setToken($token);

        $this->userDao->insert($newUser);

        $_SESSION['token'] = $token;
    }
}