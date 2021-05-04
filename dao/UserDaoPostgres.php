<?php

require_once 'models/User.php';

class UserDaoPostgres implements UserDAO {
    private PDO $pdo;

    public function __construct(PDO $driver)
    {
        $this->pdo = $driver;
    }

    private function generateUser(Array $array): User {
        $user = new User($array['id'] ?? 0);
        
        $user->setEmail($array['email'] ?? '');
        $user->setName($array['name'] ?? '');
        $user->setBirthdate($array['birthdate'] ?? '');
        $user->setCity($array['city'] ?? '');
        $user->setWork($array['work'] ?? '');
        $user->setCover($array['cover'] ?? '');
        $user->setToken($array['token'] ?? '');

        return $user;
    }

    public function findByToken(string $token): User|false
    {
        if (empty($token)) {
            return false;
        }
        
        $sql = $this->pdo->prepare("SELECT * FROM users WHERE token = :token");
        $sql->bindValue(':token', $token);
        $sql->execute();

        if (!$sql->rowCount() > 0) {
            return false;
        }

        $data = $sql->fetch(PDO::FETCH_ASSOC);

        $user = $this->generateUser($data);

        return $user;
    }
}
