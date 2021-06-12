<?php

require_once 'models/User.php';

class PostComment {
    private int $id;
    private int $idPost;
    private int $idUser;
    private string $body;
    private string $createdAt;

    // Relation of User
    private User $user;

    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getIdPost()
    {
        return $this->idPost;
    }

    public function setIdPost(int $idPost)
    {
        $this->idPost = $idPost;
    }

    public function getIdUser()
    {
        return $this->idUser;
    }

    public function setIdUser(int $idUser)
    {
        $this->idUser = $idUser;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody(string $body)
    {
        $this->body = $body;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt)
    {
        $this->createdAt = $createdAt;
    }
}

interface PostCommentDAO {
    /**
     * @return Post[]
     */
    public function getCommentsFromPost(int $idPost);
    public function insert(PostComment $postComment): void;
}
