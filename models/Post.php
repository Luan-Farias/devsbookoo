<?php

class Post {
    private int $id;
    private int $idUser;
    private string $type; // text | photo
    private string $body;
    private string $created_at;

    public function setId(int $id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setIdUser(int $idUser) {
        $this->idUser = $idUser;
    }

    public function getIdUser() {
        return $this->idUser;
    }

    public function setType(string $type) {
        $this->type = $type;
    }

    public function getType() {
        return $this->type;
    }

    public function setBody(string $body) {
        $this->body = $body;
    }

    public function getBody() {
        return $this->body;
    }

    public function setCreatedAt(string $created_at) {
        $this->created_at = $created_at;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }
}

interface PostDAO {
    public function insert(Post $post): void;
}
