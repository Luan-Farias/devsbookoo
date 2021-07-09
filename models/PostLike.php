<?php

class PostLike {
    private int $id;
    private int $idPost;
    private int $idUser;
    private string $createdAt;

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

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt)
    {
        $this->createdAt = $createdAt;
    }
}

interface PostLikeDAO {
    public function getLikeCount(int $idPost): int;
    public function isLikedByUser(int $idPost, int $idUser): bool;
    public function likeToggle(int $idPost, int $idUser): void;
    public function deleteFromPost(int $idPost): void;
}
