<?php

require_once 'models/Post.php';
require_once 'dao/UserRelationDaoPostgres.php';
require_once 'dao/UserDaoPostgres.php';

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

    /**
     * @return Post[]
     */
    public function getHomeFeed(int $idUser)
    {
        $posts = [];

        $userDao = new UserRelationDaoPostgres($this->pdo);
        $userIdList = $userDao->getFollowingsUsersIds($idUser);
        $userIdList[] = $idUser;
        
        $sql = $this
            ->pdo
            ->query('SELECT * FROM posts 
                WHERE id_user IN ('
                . implode(',', $userIdList) .
                ') ORDER BY created_at DESC');
        
        if ($sql->rowCount() > 0)
        {
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);

            $posts = $this->_postListToObject($data, $idUser);
        }

        return $posts;
    }

    /**
     * @return Post[]
     */
    public function getUserFeed(int $idUser)
    {
        $posts = [];
        
        $sql = $this
            ->pdo
            ->prepare('SELECT * FROM posts WHERE id_user = :id_user ORDER BY created_at DESC');
        $sql->bindValue(':id_user', $idUser);
        $sql->execute();
        
        if ($sql->rowCount() > 0)
        {
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);

            $posts = $this->_postListToObject($data, $idUser);
        }

        return $posts;
    }

    public function getUserPhotos(int $idUser)
    {
        $photos = [];

        $sql = $this
            ->pdo
            ->prepare('SELECT * FROM posts WHERE id_user = :id_user AND type = \'photo\' ORDER BY created_at DESC');
        $sql->bindValue(':id_user', $idUser);
        $sql->execute();

        if ($sql->rowCount() > 0)
        {
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);

            $photos = $this->_postListToObject($data, $idUser);
        }

        return $photos;
    }

    /**
     * @return Post[]
     */
    private function _postListToObject(array $postList, int $idUser)
    {
        $posts = [];
        $userDao = new UserDaoPostgres($this->pdo);

        foreach ($postList as $postItem)
        {
            $post = new Post();

            $post->setId($postItem['id']);
            $post->setIdUser($postItem['id_user']);
            $post->setType($postItem['type']);
            $post->setBody($postItem['body']);
            $post->setCreatedAt($postItem['created_at']);
            $post->setOwner(false);

            if ($post->getIdUser() === $idUser)
            {
                $post->setOwner(true);
            }

            $post->setUser($userDao->findById($post->getIdUser()));

            $post->setLikeCount(0);
            $post->setLiked(false);
            $post->setComments([]);

            $posts[] = $post;
        }
        
        return $posts;
    }
}
