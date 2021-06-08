<?php

class User {
    private int $id;
    private string $email;
    private string $password;
    private string $name;
    private string $birthdate;
    private string $city;
    private string $work;
    private string $avatar;
    private string $cover;
    private string $token;

    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $isValidEmail = filter_var($email, FILTER_VALIDATE_EMAIL);

        if ($isValidEmail)
        {
            $this->email = $email;
            return $this->email;
        }

        return false;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getBirthdate()
    {
        return $this->birthdate;
    }

    public function setBirthdate(string $birthdate)
    {
        $this->birthdate = $birthdate;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity(string $city)
    {
        $this->city = $city;
    }

    public function getWork()
    {
        return $this->work;
    }

    public function setWork(string $work)
    {
        $this->work = $work;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar)
    {
        $this->avatar = $avatar;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken(string $token)
    {
        $this->token = $token;
    }

    public function getCover()
    {
        return $this->cover;
    }

    public function setCover(string $cover)
    {
        $this->cover = $cover;
    }
}

interface UserDAO {
    public function findByToken(string $token): User | false;
    public function findByEmail(string $email): User | false;
    public function findById(int $id): User;
    public function update(User $user): void;
    public function insert(User $user): void;
}
