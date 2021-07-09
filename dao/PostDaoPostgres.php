<?php

require_once 'models/Post.php';
require_once 'dao/UserRelationDaoPostgres.php';
require_once 'dao/UserDaoPostgres.php';
require_once 'dao/PostLikeDaoPostgres.php';
require_once 'dao/PostCommentDaoPostgres.php';

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

    public function delete(int $idPost, $idUser): void
    {
        $postLikeDao = new PostLikeDaoPostgres($this->pdo);
        $postCommentDao = new PostCommentDaoPostgres($this->pdo);

        $sql = $this->pdo->prepare('SELECT * FROM posts WHERE id = :id AND id_user = :id_user');
        $sql->bindValue(':id', $idPost);
        $sql->bindValue(':id_user', $idUser);
        $sql->execute();

        if ($sql->rowCount() === 0)
            return;

        $postLikeDao->deleteFromPost($idPost);
        $postCommentDao->deleteFromPost($idPost);
        $post = $sql->fetch(PDO::FETCH_ASSOC);
        if ($post['type'] === 'photo')
        {
            $pathToFile = 'media/uploads/' . $post['body'];
            if (file_exists($pathToFile))
                unlink($pathToFile);
        }

        $sql = $this->pdo->prepare('DELETE FROM posts WHERE id = :id AND id_user = :id_user');
        $sql->bindValue(':id', $idPost);
        $sql->bindValue(':id_user', $idUser);
        $sql->execute();
    }

    public function getHomeFeed(int $idUser, int $page = 1, $perPage = 5): array
    {
        $array = [];
        $posts = [];
        $offset = ($page - 1) * $perPage;

        $userDao = new UserRelationDaoPostgres($this->pdo);
        $userIdList = $userDao->getFollowingsUsersIds($idUser);
        $userIdList[] = $idUser;
        
        $sql = $this
            ->pdo
            ->query('SELECT * FROM posts 
                WHERE id_user IN ('
                . implode(',', $userIdList) .
                ') ORDER BY created_at DESC LIMIT ' . $perPage . ' OFFSET ' . $offset);
        
        if ($sql->rowCount() > 0)
        {
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);

            $posts = $this->_postListToObject($data, $idUser);
        }

        $sql = $this
            ->pdo
            ->query('SELECT COUNT(*) as count FROM posts 
                WHERE id_user IN ('
                . implode(',', $userIdList) .
                ')');
        $totalData = $sql->fetch();
        $total = $totalData['count'];

        $array['posts'] = $posts;
        $array['pages'] = ceil($total / $perPage);
        $array['currentPage'] = $page;
        return $array;
    }

    public function getUserFeed(int $idUser, int $page = 1, int $perPage = 5): array
    {
        $array = [];
        $posts = [];
        $offset = ($page - 1) * $perPage;
        
        $sql = $this
            ->pdo
            ->prepare('SELECT * FROM posts WHERE id_user = :id_user ORDER BY created_at DESC LIMIT ' . $perPage . ' OFFSET ' . $offset);
        $sql->bindValue(':id_user', $idUser);
        $sql->execute();
        
        if ($sql->rowCount() > 0)
        {
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);

            $posts = $this->_postListToObject($data, $idUser);
        }
        $sql = $this
            ->pdo
            ->prepare('SELECT COUNT(*) as count FROM posts WHERE id_user = :id_user');
        $sql->bindValue(':id_user', $idUser);
        $sql->execute();
        $totalData = $sql->fetch();
        $total = $totalData['count'];

        $array['posts'] = $posts;
        $array['pages'] = ceil($total / $perPage);
        $array['currentPage'] = $page;

        return $array;
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
        $postLikeDao = new PostLikeDaoPostgres($this->pdo);
        $postCommentDao = new PostCommentDaoPostgres($this->pdo);

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

            $post->setLikeCount($postLikeDao->getLikeCount($post->getId()));
            $post->setLiked($postLikeDao->isLikedByUser($post->getId(), $idUser));
            $post->setComments($postCommentDao->getCommentsFromPost($post->getId()));

            $posts[] = $post;
        }
        
        return $posts;
    }
}
