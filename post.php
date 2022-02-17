<?php
// обработка только ajax запросов (при других запросах завершаем выполнение скрипта)
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
    header("HTTP/1.0 404 Not Found");
    exit();
}
// обработка данных, полученых только методом POST (при остальных методах завершаем выполнение скрипта)
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("HTTP/1.0 404 Not Found");
    exit();
}

require_once('include/JsonDb.php');
require_once('include/User.php');

try {
    $user_db = new JsonDb("./db/users.json");
} catch (Exception $e) {
    exit($e);
}

function is_exists($key, $value, $db): bool {
    return count($db->select($key, $value)) !== 0;
}

$errors = array(
    'user_reg' => " <- Такой пользователь уже зарегистрирован!",
    'user_not' => " <- Такого пользователя не существует!",
    'email_reg' => " <- Такой E-mail уже зарегистрирован!",
    'confirm_not' => " <- Пароли не совпадают!",
    'pass_not' => " <- Неверный пароль!",
    'hash_not' => " <- Ошибка создания хэша пароля!"
);

$data['result'] = 'success';

if ($_POST['form'] == 'login') { // если данные отправлены из формы входа
    if (isset($_POST['login_entry']) && isset($_POST['password_entry'])) {
        $login = htmlspecialchars($_POST['login_entry']);
        $password = htmlspecialchars($_POST['password_entry']);
        if (is_exists('login', $login, $user_db)) { // если пользователь есть в базе
            $user_hash = $user_db->selectRow('login', $login, 'passwd');
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

        // password
        $password = htmlspecialchars($_POST['password']);
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        if (!$pass_hash) {
            $data['password'] = $errors['hash_not'];
            $data['result'] = 'error';
        }

        // confirm
        $confirm = htmlspecialchars($_POST['confirm']);
        $confirm_hash = password_hash($confirm, PASSWORD_DEFAULT);
        if (!$confirm_hash) {
            $data['confirm'] = $errors['hash_not'];
            $data['result'] = 'error';
        } elseif ($password !== $confirm) {
            $data['confirm'] = $errors['confirm_not'];
            $data['result'] = 'error';
        }

        // email
        $email = htmlspecialchars($_POST['email']); // защита от передачи скриптов в запросе
        if (is_exists('email', $email, $user_db)) {
            $data['email'] = $errors['email_reg'];
            $data['result'] = 'error';
        }

        // name
        $name = htmlspecialchars($_POST['name']); // защита от передачи скриптов в запросе

        if ($data['result'] == 'success') { // если ошибок нет, то добавляем пользователя в базу
            $user_new = new User($login, $pass_hash, $email, $name);
            $user_db->insert($user_new->getUser());
        }
    } else {
        exit(); // если каких-то данных нет, роскомнадзорнуемся
    }
}

echo json_encode($data); // отправляем результат
