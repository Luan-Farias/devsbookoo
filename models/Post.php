<?php

require_once 'models/User.php';

class Post {
    private int $id;
    private int $idUser;
    private string $type; // text | photo
    private string $body;
    private string $created_at;

    // If logged user is post owner
    private bool $owner;

    // Relation of User
    private User $user;
    
    // Counts Of Like Of A Post
    private int $likeCount;

    // If logged user liked the post
    private bool $liked;

    // Post comments
    private array $comments;

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setIdUser(int $idUser)
    {
        $this->idUser = $idUser;
    }

    public function getIdUser()
    {
        return $this->idUser;
    }

    public function setType(string $type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setBody(string $body)
    {
        $this->body = $body;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setCreatedAt(string $created_at)
    {
        $this->created_at = $created_at;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setOwner(bool $owner)
    {
        $this->owner = $owner;
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setLikeCount(int $likeCount)
    {
        $this->likeCount = $likeCount;
    }

    public function getLikeCount()
    {
        return $this->likeCount;
    }

    public function setLiked(bool $liked)
    {
        $this->liked = $liked;
    }

    public function getLiked()
    {
        return $this->liked;
    }

    public function setComments(array $comments)
    {
        $this->comments = $comments;
    }

    public function getComments()
    {
        return $this->comments;
    }
}

interface PostDAO {
    public function insert(Post $post): void;
    /**
     * @return Post[]
     */
    public function getHomeFeed(int $idUser);
    /**
     * @return Post[]
     */
    public function getUserFeed(int $idUser);
    /**
     * @return Post[]
     */
    public function getUserPhotos(int $idUser);
}
