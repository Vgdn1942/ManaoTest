<?php

class Validate {
    private array $field = array('login', 'password', 'email', 'name');
    private array $errors = array(
        'is_empty' => " <- Поле не может быть пустым!",
        'small_six' => " <- Длина должна быть больше 6 символов!",
        'small_two' => " <- Длина должна быть больше 2 символов!",
        'is_space' => " <- Поле не должно содержать пробелы!",
        'is_reg' => " <- Такой пользователь уже существует!",
        'email_wrong' => " <- Неверный формат email!",
        'email_reg' => " <- Такой email уже существует!",
        'letter_digit' => " <- Пароль должен состоять из цифр и букв!",
        'confirm_pass' => " <- Пароли не совпадают!",);

    /*
    public function __construct($name, $value) {
        $this->name = $name;
        $this->value = $value;
    }
    */
    public function validate($name, $value) {
        if (in_array($name, $this->field)) {
            switch ($name) {
                case "login":
                    $this->val_login($name);
                    break;
                case "password":
                    echo "i это шоколадка";
                    break;
                /*
            case "confirm":
                echo "i это шоколадка";
                break;
            case "email":
                echo "i это пирог";
                break;
            case "name":
                echo "i это пирог";
                break;
                */
            }
        }
    }

    private function val_login($value): string {
        if (strlen($value) == 0) {
            return $this->errors['is_empty'];
        }
        elseif (strlen($value) < 6) {
            return $this->errors['small_six'];
        }
        return "";
    }
}