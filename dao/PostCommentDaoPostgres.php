<?php

require_once 'models/PostComment.php';
require_once 'dao/UserDaoPostgres.php';

class PostCommentDaoPostgres implements PostCommentDAO {
    private PDO $pdo;

    public function __construct(PDO $driver)
    {
        $this->pdo = $driver;
    }
    
    /**
     * @return PostComment[]
     */
    public function getCommentsFromPost(int $idPost)
    {
        $comments = [];

        $sql = $this
            ->pdo
            ->prepare('SELECT * FROM post_comments WHERE id_post = :id_post');
        $sql->bindValue(':id_post', $idPost);
        $sql->execute();

        if ($sql->rowCount() > 0)
        {
            $userDao = new UserDaoPostgres($this->pdo);

            $data = $sql->fetchAll(PDO::FETCH_ASSOC);

            foreach ($data as $item)
            {
                $comment = new PostComment();

                $comment->setId($item['id']);
                $comment->setIdUser($item['id_user']);
                $comment->setIdPost($item['id_post']);
                $comment->setBody($item['body']);
                $comment->setCreatedAt($item['created_at']);

                $comment->setUser($userDao->findById($comment->getIdUser()));

                $comments[] = $comment;
            }
        }

        return $comments;
    }

    public function insert(PostComment $postComment): void
    {
        $sql = $this
            ->pdo
            ->prepare('INSERT
                INTO post_comments(id_post, id_user, body, created_at)
                VALUES (:id_post, :id_user, :body, :created_at) 
            ');
        $sql->bindValue(':id_post', $postComment->getIdPost());
        $sql->bindValue(':id_user', $postComment->getIdUser());
        $sql->bindValue(':body', $postComment->getBody());
        $sql->bindValue(':created_at', $postComment->getCreatedAt());
        $sql->execute();
    }

    public function deleteFromPost(int $idPost): void
    {
        $sql = $this->pdo->prepare("DELETE FROM post_comments WHERE id_post = :id_post");
        $sql->bindValue(':id_post', $idPost);
        $sql->execute();
    }
}
