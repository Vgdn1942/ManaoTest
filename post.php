<?php
// проверяем, что что это ajax запрос
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    /*
    Поле login: (unique)    [валидация : минимум 6 символов ]
+        1. Оставить поле незаполненным и попытаться отправить форму.
+        2. Проверить валидность длины логина. 6
+        3. Проверить ввод логина с пробелами между/в начале /в конце символов.
+       Проверить отправку формы только из пробелов.
        Проверить отправку формы  уже зарегистрированного логина в БД.

    Поле password:
+        1. Оставить поле незаполненным и попытаться отправить форму.
+        2. Проверить валидность длины пароля. 6
+        3. Проверить ввод пароля с пробелом между/ в начале / в конце / символов.
+        Проверить шифрование пароля при вводе (********).
        Проверить ввод валидного пароля. [валидация : минимум 6 символов , обязательно должны состоять из цифр и букв]
+        Проверить ввод валидного пароля + спец.символы.
        Проверить отправку формы только из пробелов в поле.

    Поле confirm_password:
        1. Оставить поле незаполненным и попытаться отправить форму.
        Проверить ввод пароля отличного от пароля в поле password.
        Проверить ввод пароля совпадающего с паролем в поле password.

    Поле email:
        1. Оставить поле незаполненным и попытаться отправить форму.
        3. Проверить ввод адреса с пробелом между/ в начале / в конце / символов.
        Проверить ввод адреса с двойным символом @.
        Проверить ввод адреса без доменной части.
        Проверить ввод адреса без точки в доменной части.
        Проверить ввод адреса уже зарегистрированного в БД.

    Поле name:
        1. Оставить поле незаполненным и попытаться отправить форму.
        2. Проверить валидность длины имени. (2 символа , только буквы)
        3. Проверить ввод имени с пробелами между/в начале /в конце букв.
        Проверить границы длины имени.
        Проверить ввод имени только из пробелов .
    */

checkForm();

} else { // если это не ajax
    exit;
}

function checkForm () {
    $errors = array(
        'no_errors' => "ok",
        'is_empty' => " <- Поле не может быть пустым!",
        'small_six' => " <- Длина должна быть больше 6 символов!",
        'small_two' => " <- Длина должна быть больше 2 символов!",
        'is_space' => " <- Поле не должно содержать пробелы!",
        'all_space' => " <- Поле не может быть состоять только из пробелов!",
        'is_reg' => " <- Такой пользователь уже существует!",
        'email_wrong' => " <- Неверный формат email!",
        'email_reg' => " <- Такой email уже существует!",
        'letter_digit' => " <- Пароль должен состоять только из цифр и букв!",
        'confirm_pass' => " <- Пароли не совпадают!",
    );

    $result = array();
    $form = $_POST['form'];

    if ($form == 'login') {
        $login_array = 'login_entry';
        $pass_array = 'password_entry';
        $login_id = $_POST['login_entry'];
        $pass_id = $_POST['password_entry'];

        if (strlen($login_id) == 0) { // проверка на нулевую длину
            $login = array($login_array, $errors[is_empty]);
        } elseif (ctype_space($login_id)) { // проверка на отправку формы только из пробелов
            $login = array($login_array, $errors[all_space]);
        } elseif (strlen($login_id) < 6) { // проверка на количество символов
            $login = array($login_array, $errors[small_six]);
        } elseif (strripos($login_id, ' ') !== false) { // проверка на содержание пробелов
            $login = array($login_array, $errors[is_space]);
        } else {
            $login = array($login_array, $errors[no_errors]);
        }
        $result[] = $login;

        if (strlen($pass_id) == 0) { // проверка на нулевую длину
            $password = array($pass_array, $errors[is_empty]);
        } elseif (ctype_space($pass_id)) { // проверка на отправку формы только из пробелов
            $password = array($pass_array, $errors[all_space]);
        } elseif (strlen($pass_id) < 6) { // проверка на количество символов
            $password = array($pass_array, $errors[small_six]);
        } elseif (strripos($pass_id, ' ') !== false) { // проверка на содержание пробелов
            $password = array($pass_array, $errors[is_space]);
        } elseif (!ctype_alnum($pass_id)) { // проверка на спецсимволы
            $password = array($pass_array, $errors[letter_digit]);
        } elseif (ctype_alpha($pass_id) || ctype_digit($pass_id)) { // проверка на цифры и буквы
            $password = array($pass_array, $errors[letter_digit]);
        } else { // ошибок нет
            $password = array($pass_array, $errors[no_errors]);
        }
        $result[] = $password;

    } elseif ($form == 'reg') {

        $login_array = 'login';
        $pass_array = 'password';
        $login_id = $_POST['login'];
        $pass_id = $_POST['password'];

        if (strlen($login_id) == 0) { // проверка на нулевую длину
            $login = array($login_array, $errors[is_empty]);
        } elseif (ctype_space($login_id)) { // проверка на отправку формы только из пробелов
            $login = array($login_array, $errors[all_space]);
        } elseif (strlen($login_id) < 6) { // проверка на количество символов
            $login = array($login_array, $errors[small_six]);
        } elseif (strripos($login_id, ' ') !== false) { // проверка на содержание пробелов
            $login = array($login_array, $errors[is_space]);
        } else {
            $login = array($login_array, $errors[no_errors]);
        }
        $result[] = $login;

        if (strlen($pass_id) == 0) { // проверка на нулевую длину
            $password = array($pass_array, $errors[is_empty]);
        } elseif (ctype_space($pass_id)) { // проверка на отправку формы только из пробелов
            $password = array($pass_array, $errors[all_space]);
        } elseif (strlen($pass_id) < 6) { // проверка на количество символов
            $password = array($pass_array, $errors[small_six]);
        } elseif (strripos($pass_id, ' ') !== false) { // проверка на содержание пробелов
            $password = array($pass_array, $errors[is_space]);
        } elseif (!ctype_alnum($pass_id)) { // проверка на спецсимволы
            $password = array($pass_array, $errors[letter_digit]);
        } elseif (ctype_alpha($pass_id) || ctype_digit($pass_id)) { // проверка на цифры и буквы
            $password = array($pass_array, $errors[letter_digit]);
        } else { // ошибок нет
            $password = array($pass_array, $errors[no_errors]);
        }
        $result[] = $password;
    }

    echo json_encode($result); // json ответ
}