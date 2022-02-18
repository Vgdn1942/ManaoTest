<?php

class User {

    private $login;
    private $passwd;
    private $email;
    private $name;

    /**
     * @param $_login
     * @param $_passwd
     * @param $_email
     * @param $_name
     */
    public function __construct($_login, $_passwd, $_email, $_name) {
        $this->login = $_login;
        $this->passwd = $_passwd;
        $this->email = $_email;
        $this->name = $_name;
    }

    /**
     * @return array
     */
    public function getUser(): array {
        return array(
            'login' => $this->login,
            'passwd' => $this->passwd,
            'email' => $this->email,
            'name' => $this->name
        );
    }

    /**
     * @return false|string
     */
    public function getUserJson() {
        return json_encode(self::getUser());
    }

    /**
     * @return string
     */
    public function getLogin(): string {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getHash(): string {
        return $this->passwd;
    }

    /**
     * @return string
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }
}