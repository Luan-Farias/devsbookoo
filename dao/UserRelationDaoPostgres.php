<?php

require_once 'models/UserRelation.php';
require_once './dao/UserRelationDaoPostgres.php';

class UserRelationDaoPostgres implements UserRelationDAO {
    private PDO $pdo;

    public function __construct(PDO $driver)
    {
        $this->pdo = $driver;
    }

    public function insert(UserRelation $userRelation): void
    {
        
    }

    /**
     * @return int[]
     */
    public function getFollowingsUsersIds(int $idUser)
    {
        $users = [];

        $sql = $this->pdo->prepare('SELECT user_to FROM user_relations WHERE user_from = :user_from');
        $sql->bindValue(':user_from', $idUser);
        $sql->execute();

        if ($sql->rowCount() > 0)
        {
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);

            foreach ($data as $item)
            {
                $users[] = $item['user_to'];
            }
        }

        return $users;
    }

    /**
     * @return int[]
     */
    public function getFollowersUsersIds(int $idUser)
    {
        $users = [];

        $sql = $this->pdo->prepare('SELECT user_from FROM user_relations WHERE user_to = :user_to');
        $sql->bindValue(':user_to', $idUser);
        $sql->execute();

        if ($sql->rowCount() > 0)
        {
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);

            foreach ($data as $item)
            {
                $users[] = $item['user_from'];
            }
        }

        return $users;
    }
}