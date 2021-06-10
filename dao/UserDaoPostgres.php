<?php

require_once 'models/User.php';
require_once 'dao/UserRelationDaoPostgres.php';
require_once 'dao/PostDaoPostgres.php';

class UserDaoPostgres implements UserDAO {
    private PDO $pdo;

    public function __construct(PDO $driver)
    {
        $this->pdo = $driver;
    }

    private function generateUser(Array $array, bool $getAllRelations = false): User
    {
        $user = new User();
        
        $user->setId($array['id'] ?? 0);
        
        $user->setEmail($array['email'] ?? '');
        $user->setName($array['name'] ?? '');
        $user->setBirthdate($array['birthdate'] ?? '');
        $user->setPassword($array['password']);
        $user->setCity($array['city'] ?? '');
        $user->setAvatar($array['avatar']);
        $user->setWork($array['work'] ?? '');
        $user->setCover($array['cover'] ?? '');
        $user->setToken($array['token'] ?? '');

        if ($getAllRelations)
        {
            $userRelationDao = new UserRelationDaoPostgres($this->pdo);
            $postDao = new PostDaoPostgres($this->pdo);

            $followers = [];
            $followersUsersId = $userRelationDao->getFollowersUsersIds($user->getId());
            foreach($followersUsersId as $followerUserId) {
                $follower = $this->findById($followerUserId);

                $followers[] = $follower;
            }
            $user->setFollowers($followers);

            $followings = [];
            $followingUsersId = $userRelationDao->getFollowingsUsersIds($user->getId());
            foreach($followingUsersId as $followingUserId) {
                $following = $this->findById($followingUserId);

                $followings[] = $following;
            }
            $user->setFollowing($followings);

            $userPhotos = $postDao->getUserPhotos($user->getId());
            $user->setPhotos($userPhotos);
        }

        return $user;
    }

    public function findByToken(string $token): User|false
    {
        if (empty($token))
        {
            return false;
        }
        
        $sql = $this->pdo->prepare("SELECT * FROM users WHERE token = :token");
        $sql->bindValue(':token', $token);
        $sql->execute();

        if (!$sql->rowCount() > 0)
        {
            return false;
        }

        $data = $sql->fetch(PDO::FETCH_ASSOC);

        $user = $this->generateUser($data);

        return $user;
    }

    public function findByEmail(string $email): User|false
    {
        if (empty($email))
        {
            return false;
        }
        
        $sql = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $sql->bindValue(':email', $email);
        $sql->execute();

        if (!$sql->rowCount() > 0)
        {
            return false;
        }

        $data = $sql->fetch(PDO::FETCH_ASSOC);

        $user = $this->generateUser($data);

        return $user;
    }

    public function findById(int $id, bool $getAllRelations = false): User
    {
        if (empty($id))
        {
            return false;
        }
        
        $sql = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $sql->bindValue(':id', $id);
        $sql->execute();

        if (!$sql->rowCount() > 0)
        {
            return false;
        }

        $data = $sql->fetch(PDO::FETCH_ASSOC);

        $user = $this->generateUser($data, $getAllRelations);
        $user->setPassword($data['password'] ?? '');

        return $user;
    }

    public function update(User $user): void
    {
        $sql = $this->pdo->prepare("UPDATE users SET
            email = :email,
            password = :password,
            name = :name,
            birthdate = :birthdate,
            city = :city,
            work = :work,
            avatar = :avatar,
            cover = :cover,
            token = :token
            WHERE id = :id
        ");

        $sql->bindValue(':email', $user->getEmail());
        $sql->bindValue(':password', $user->getPassword());
        $sql->bindValue(':name', $user->getName());
        $sql->bindValue(':birthdate', $user->getBirthdate());
        $sql->bindValue(':city', $user->getCity());
        $sql->bindValue(':work', $user->getWork());
        $sql->bindValue(':avatar', $user->getAvatar());
        $sql->bindValue(':cover', $user->getCover());
        $sql->bindValue(':token', $user->getToken());
        $sql->bindValue(':id', $user->getId());
        $sql->execute();
    }

    public function insert(User $user): void
    {
        $sql = $this->pdo->prepare("INSERT INTO users (
            email, password, name, birthdate, token
            ) VALUES (
                :email, :password, :name, :birthdate, :token
            )");
        
        $sql->bindValue(':email', $user->getEmail());
        $sql->bindValue(':password', $user->getPassword());
        $sql->bindValue(':name', $user->getName());
        $sql->bindValue(':birthdate', $user->getBirthdate());
        $sql->bindValue(':token', $user->getToken());
        $sql->execute();
    }
}
