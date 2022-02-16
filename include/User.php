<?php

class User {
    private $login;
    private $passwd;
    private $email;
    private $name;

    public function __construct($_login, $_passwd, $_email, $_name) {
        $this->login = $_login;
        $this->passwd = $_passwd;
        $this->email = $_email;
        $this->name = $_name;
    }

    public function getUser(): array {
        return array(
            'login' => $this->login,
            'passwd' => $this->passwd,
            'email' => $this->email,
            'name' => $this->name
        );
    }

    public function getUserJson() {
        return json_encode(self::getUser());
    }

    public function getLogin(): string {
        return $this->login;
    }

    public function getHash(): string {
        return $this->passwd;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getName(): string {
        return $this->name;
    }
}