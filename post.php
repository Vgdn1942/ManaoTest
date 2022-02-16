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
} catch (Exception $e) {
    //todo remove log
    file_put_contents("log/server.log", ' Exception ' . $e, FILE_APPEND);
    exit($e);
}

function is_exists($key, $value, $db): bool {
    return count($db->select($key, $value)) !== 0;
}

$errors = array(
    'is_empty' => " <- Поле не может быть пустым!",
    'small_six' => " <- Длина должна быть больше 6 символов!",
    'small_two' => " <- Длина должна быть больше 2 символов!",
    'is_space' => " <- Поле не должно содержать пробелы!",
    'all_space' => " <- Поле не может быть состоять только из пробелов!",
    'user_reg' => " <- Такой пользователь уже зарегистрирован!",
    'user_not' => " <- Такого пользователя не существует!",
    'email_wrong' => " <- Неверный формат email!",
    'email_reg' => " <- Такой E-mail уже зарегистрирован!",
    'letter_digit' => " <- Пароль должен состоять только из цифр и букв!",
    'confirm_pass' => " <- Пароли не совпадают!",
    'pass_not' => " <- Неверный пароль!",
    'hash_pass' => " <- Ошибка создания хэша пароля!");

$data['result'] = 'success';

if ($_POST['form'] == 'login') { // если данные отправлены из формы входа
    if (isset($_POST['login_entry']) && isset($_POST['password_entry'])) {
        $login = htmlspecialchars($_POST['login_entry']);
        $password = htmlspecialchars($_POST['password_entry']);
        if (is_exists('login', $login, $user_db)) { // если пользователь есть в базе
            $user = $user_db->select('login', $login);
            $user_hash = $user['passwd']; // получаем из базы хэш его пароля
            // todo remove log
            file_put_contents("log/server.log", ' Pass hash: ' . implode("|",$user), FILE_APPEND);
            if (password_verify($password, $user_hash)) { // если пароль совпадает с хэшем запускаем сессию
                session_start();
                $_SESSION['login'] = $login;
                $_SESSION['name'] = $login;
            } else {
                $data['password_entry'] = $errors['pass_not'];
                $data['result'] = 'error';
            }
        } else {
            $data['login_entry'] = $errors['user_not'];
            $data['result'] = 'error';
        }
        //todo remove log
        file_put_contents("log/server.log", ' Sign in ok \n', FILE_APPEND);
    }
} elseif ($_POST['form'] == 'reg') { // $form == 'reg' // если данные отправлены из формы регистрации
    // проверяем присутствие всех данных из формы
    if (isset($_POST['login']) && isset($_POST['password']) && isset($_POST['confirm']) && isset($_POST['email']) && isset($_POST['name'])) {

        // login
        $login = htmlspecialchars($_POST['login']); // защита от передачи скриптов в запросе
        if (is_exists('login', $login, $user_db)) {
            $data['login'] = $errors['user_reg'];
            $data['result'] = 'error';
        }
        // todo remove log
        file_put_contents("log/server.log", " result_login: " . $data['result'], FILE_APPEND);

        // password
        $password = htmlspecialchars($_POST['password']);
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        if (!$pass_hash) {
            $data['password'] = $errors['hash_pass'];
            $data['result'] = 'error';
        }
        // todo remove log
        file_put_contents("log/server.log", " result_pass: " . $data['result'], FILE_APPEND);

        // confirm
        $confirm = htmlspecialchars($_POST['confirm']);
        $confirm_hash = password_hash($confirm, PASSWORD_DEFAULT);
        if (!$confirm_hash) {
            $data['confirm'] = $errors['hash_pass'];
            $data['result'] = 'error';
        } elseif ($password !== $confirm) {
            $data['confirm'] = $errors['confirm_pass'];
            $data['result'] = 'error';
        }
        // todo remove log
        file_put_contents("log/server.log", " result_confirm: " . $data['result'], FILE_APPEND);

        // email
        $email = htmlspecialchars($_POST['email']); // защита от передачи скриптов в запросе
        if (is_exists('email', $email, $user_db)) {
            $data['email'] = $errors['email_reg'];
            $data['result'] = 'error';
        }
        // todo remove log
        file_put_contents("log/server.log", " res_email: " . $res ? "true; " : "false; ", FILE_APPEND);

        // name
        $name = htmlspecialchars($_POST['name']); // защита от передачи скриптов в запросе

        if ($data['result'] == 'success') { // если ошибок нет
            $user_new = new User($login, $pass_hash, $email, $name);
            $user_db->insert($user_new->getUser());
        }
    } else {
        // todo remove log
        file_put_contents("log/server.log", " not any data ", FILE_APPEND);
        exit(); // если каких-то данных нет, роскомнадзорнуемся
    }
}

echo json_encode($data); // отправляем результат
