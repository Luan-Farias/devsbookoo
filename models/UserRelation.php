<?php

class UserRelation {
    private int $id;
    private int $userFrom;
    private int $userTo;

    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getUserFrom()
    {
        return $this->userFrom;
    }

    public function setUserFrom(int $userFrom)
    {
        $this->userFrom = $userFrom;
    }

    public function getUserTo()
    {
        return $this->userTo;
    }

    public function setUserTo(int $userTo)
    {
        $this->userTo = $userTo;
    }
}

interface UserRelationDAO {
    public function insert(UserRelation $userRelation): void;
    /**
     * @return int[]
     */
    public function getFollowingsUsersIds(int $idUser);
    /**
     * @return int[]
     */
    public function getFollowersUsersIds(int $idUser);
}
