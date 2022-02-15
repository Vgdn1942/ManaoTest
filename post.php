<?php
// обработка только ajax запросов (при других запросах завершаем выполнение скрипта)
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
    exit();
}
// обработка данных, полученых только методом POST (при остальных методах завершаем выполнение скрипта)
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

require_once('include/JsonDb.php');
require_once('include/User.php');

try {
    $user_db = new JsonDb("./db/users.json");
} catch (Exception $e) {}

function is_exists($key, $value, $db): bool {
    return count($db->select($key, $value)) !== 0;
}

if (isset($_POST['form']) && $_POST['form'] == 'login') { // если данные отправлены из формы входа
    if (isset($_POST['login_entry']) && isset($_POST['password_entry'])) {
        $login = htmlspecialchars($_POST['login_entry']);
        $password = htmlspecialchars($_POST['password_entry']);
        if (is_exists('login', $login, $user_db)) {
            session_start();
            $_SESSION['login'] = $login;
        }
    }
} else { // $form == 'reg' // если данные отправлены из формы регистрации
    // проверяем присутствие всех данных из формы
    if (isset($_POST['login']) && isset($_POST['password']) && isset($_POST['confirm_password']) && isset($_POST['email']) && isset($_POST['name'])) {
        $login = htmlspecialchars($_POST['login']); // защита от передачи скриптов в запросе
        $password = htmlspecialchars($_POST['password']);
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        if ($pass_hash) {
            exit("Ошибка создания хэша пароля!");
        }
        $confirm_password = htmlspecialchars($_POST['confirm_password']);
        $confirm_hash = password_hash($confirm_password, PASSWORD_DEFAULT);
        if ($confirm_hash) {
            exit("Ошибка создания хэша пароля!");
        }
        $email = htmlspecialchars($_POST['email']); // защита от передачи скриптов в запросе
        $name = htmlspecialchars($_POST['name']); // защита от передачи скриптов в запросе
    } else {
        exit(); // если каких-то данных нет, роскомнадзорнуемся
    }
}

/// ToDo remove this code
try {
    $user = new User($_POST['login'], $_POST['password'], $_POST['email'], $_POST['name']);

    $log = $user_db->select('login', $_POST['login']);
    //$us = $user->getName();
    //$log = $user_db->selectAll();

    //$log = json_encode($log);
    $log = is_exists('login', $_POST['login'], $user_db);
    //$log = count($log);
    file_put_contents("log/server.log", $log ? "true" : "false", FILE_APPEND);

    /*
    $user = array(
            'login' => $_POST['login'],
            'password' => $_POST['password'],
            'email' => $_POST['email'],
            'name' => $_POST['name']
    );
    */

    //$user_db->insert($user->getUser());
} catch (Exception $e) {
}


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
    'confirm_pass' => " <- Пароли не совпадают!"
);

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
$password = array($pass_id, $errors['no_errors']);
$result[] = $password;

if ($form == 'reg') {
    $confirm_str = $_POST['confirm'];
    $email_str = $_POST['email'];
    $name_str = $_POST['name'];

    // confirm
    if ($confirm_str !== $pass_str) { // проверка на совпадение паролей
        $confirm = array('confirm', $errors['confirm_pass']);
    } else { // ошибок нет
        $confirm = array('confirm', $errors['no_errors']);
    }
    $result[] = $confirm;

    // email
    $email = array('email', $errors['no_errors']);
    $result[] = $email;

    // name
    $name = array('name', $errors['no_errors']);
    $result[] = $name;
}

echo json_encode($result); // отправляем результат
