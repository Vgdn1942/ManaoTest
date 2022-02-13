<?php
// обработка только ajax запросов (при других запросах завершаем выполнение скрипта)
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
    exit();
}
// обработка данных, посланных только методом POST (при остальных методах завершаем выполнение скрипта)
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    exit();
}
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
+        Проверить ввод валидного пароля. [валидация : минимум 6 символов , обязательно должны состоять из цифр и букв]
+        Проверить ввод валидного пароля + спец.символы.
+        Проверить отправку формы только из пробелов в поле.

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

    //include_once 'include/JsonDb.php';
    //include( 'include/JsonDb.php' );
    require_once('include/JsonDb.php');
    //$json_db = new JsonDb($_SERVER['DOCUMENT_ROOT'] . "/db/");
    //$json_db = new JsonDb();
    /*
    try {
        $json_db->insert('users.json',
            [
                'login' => $_POST['login'],
                'password' => $_POST['password'],
                'email' => $_POST['email'],
                'name' => $_POST['name']
            ]);
    } catch (Exception $e) {
    }
*/
    checkForm();

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

    $data['result'] = 'success';
    $result = array();
    $form = $_POST['form'];

    if ($form == 'login') {
        $login_id = 'login_entry';
        $pass_id = 'password_entry';
    } elseif ($form == 'reg') {
        $login_id = 'login';
        $pass_id = 'password';
    }
    $login_str = $_POST[$login_id];
    $pass_str = $_POST[$pass_id];

    // login
    if (strlen($login_str) == 0) { // проверка на нулевую длину
        $login = array($login_id, $errors['is_empty']);
    } elseif (ctype_space($login_str)) { // проверка на отправку формы только из пробелов
        $login = array($login_id, $errors['all_space']);
    } elseif (strlen($login_str) < 6) { // проверка на количество символов
        $login = array($login_id, $errors['small_six']);
    } elseif (strripos($login_str, ' ') !== false) { // проверка на содержание пробелов
        $login = array($login_id, $errors['is_space']);
    } else {
        $login = array($login_id, $errors['no_errors']);
    }
    $result[] = $login;

    // password
    if (strlen($pass_str) == 0) { // проверка на нулевую длину
        $password = array($pass_id, $errors['is_empty']);
    } elseif (ctype_space($pass_str)) { // проверка на отправку формы только из пробелов
        $password = array($pass_id, $errors['all_space']);
    } elseif (strlen($pass_str) < 6) { // проверка на количество символов
        $password = array($pass_id, $errors['small_six']);
    } elseif (strripos($pass_str, ' ') !== false) { // проверка на содержание пробелов
        $password = array($pass_id, $errors['is_space']);
    } elseif (!ctype_alnum($pass_str)) { // проверка на спецсимволы
        $password = array($pass_id, $errors['letter_digit']);
    } elseif (ctype_alpha($pass_str) || ctype_digit($pass_str)) { // проверка на цифры и буквы
        $password = array($pass_id, $errors['letter_digit']);
    } else { // ошибок нет
        $password = array($pass_id, $errors['no_errors']);
    }
    $result[] = $password;

    if ($form == 'reg') {
        $confirm_str = $_POST['confirm'];
        $email_str = $_POST['email'];
        $name_str = $_POST['name'];

        // confirm
        if (strlen($confirm_str) == 0) { // проверка на нулевую длину
            $confirm = array('confirm', $errors['is_empty']);
        } elseif ($confirm_str !== $pass_str) { // проверка на совпадение паролей
            $confirm = array('confirm', $errors['confirm_pass']);
        } else { // ошибок нет
            $confirm = array('confirm', $errors['no_errors']);
        }
        $result[] = $confirm;

        // email
        if (strlen($email_str) == 0) { // проверка на нулевую длину
            $email = array('email', $errors['is_empty']);
        } elseif (!filter_var($email_str, FILTER_VALIDATE_EMAIL)) { // проверка на email
            $email = array('email', $errors['email_wrong']);
        } else { // ошибок нет
            $email = array('email', $errors['no_errors']);
        }
        $result[] = $email;

        if (strlen($name_str) == 0) { // проверка на нулевую длину
            $name = array('name', $errors['is_empty']);
        } elseif (ctype_space($name_str)) { // проверка на отправку формы только из пробелов
            $name = array('name', $errors['all_space']);
        } elseif (strlen($name_str) < 2) { // проверка на количество символов
            $name = array('name', $errors['small_two']);
        } elseif (strripos($name_str, ' ') !== false) { // проверка на содержание пробелов
            $name = array('name', $errors['is_space']);
        } else {
            $name = array('name', $errors['no_errors']);
        }
        $result[] = $name;
    }

    $data['result'] = 'error';
    $result[] = $data;
    echo json_encode($result);
}