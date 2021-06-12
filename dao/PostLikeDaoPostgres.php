<?php

require_once 'models/PostLike.php';

class PostLikeDaoPostgres implements PostLikeDAO {
    private PDO $pdo;

    public function __construct(PDO $driver)
    {
        $this->pdo = $driver;
    }
    
    public function getLikeCount(int $idPost): int
    {
        $sql = $this
            ->pdo
            ->prepare('SELECT COUNT(*) as like_count FROM post_likes WHERE id_post = :id_post');
        $sql->bindValue(':id_post', $idPost);
        $sql->execute();

        $data = $sql->fetch(PDO::FETCH_ASSOC);
        
        return $data['like_count'];
    }

    public function isLikedByUser(int $idPost, int $idUser): bool
    {
        $sql = $this
            ->pdo
            ->prepare('SELECT * FROM post_likes WHERE id_post = :id_post AND id_user = :id_user');
        $sql->bindValue(':id_post', $idPost);
        $sql->bindValue(':id_user', $idUser);
        $sql->execute();

        if ($sql->rowCount() > 0)
            return true;

        return false;
    }

    public function likeToggle(int $idPost, int $idUser): void
    {
        $isLikedByUser = $this->isLikedByUser($idPost, $idUser);
        $sql = null;

        if (!$isLikedByUser) {
            $sql = $this
                ->pdo
                ->prepare(
                    'INSERT INTO post_likes(id_post, id_user, created_at)
                    VALUES (:id_post, :id_user, NOW())'
                );
        }
        else
        {
            $sql = $this
            ->pdo
            ->prepare('DELETE FROM post_likes WHERE id_post = :id_post AND id_user = :id_user');
        }
        
        $sql->bindValue(':id_post', $idPost);
        $sql->bindValue(':id_user', $idUser);
        $sql->execute();
    }
}
