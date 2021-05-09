<?php

require_once 'models/Post.php';

class PostDaoPostgres implements PostDAO {
    private PDO $pdo;

    public function __construct(PDO $driver)
    {
        $this->pdo = $driver;
    }

    public function insert(Post $post): void
    {
        $sql = $this->pdo->prepare('INSERT INTO posts (
                id_user, type, body, created_at
            ) VALUES (
                :id_user, :type, :body, :created_at
            )');

        $sql->bindValue(':id_user', $post->getIdUser());
        $sql->bindValue(':type', $post->getType());
        $sql->bindValue(':body', $post->getBody());
        $sql->bindValue(':created_at', $post->getCreatedAt());
        $sql->execute();
    }
}
