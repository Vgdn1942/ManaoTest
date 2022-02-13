<?php

class User {
    private string $login;
    private string $passwd;
    private string $email;
    private string $name;

    public function __construct($login, $passwd, $email, $name) {
        $this->login = $login;
        $this->passwd = $passwd;
        $this->email = $email;
        $this->name = $name;
    }

    public function getUser(): array {
        return array(
            'login' => $this->login,
            'passwd' => $this->passwd,
            'email' => $this->email,
            'name' => $this->name);
    }

    public function getUserJson() {
        return json_encode(self::getUser());
    }

}