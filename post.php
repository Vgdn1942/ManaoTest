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

$data['result'] = 'success';

try {
    $user_db = new JsonDb("./db/users.json");
} catch (Exception $e) {
    $data['result'] = 'error';
    echo json_encode($data); // отправляем результат
    exit($e);
}

function isExists($key, $value, $db): bool {
    return count($db->select($key, $value)) !== 0;
}

function generateSalt(): string {
    $salt = '';
    $saltLength = 8; // длина соли
    for ($i = 0; $i < $saltLength; $i++) {
        $salt .= chr(mt_rand(33, 126)); // символ из ASCII-table
    }
    return $salt;
}

$errors = array(
    'user_reg' => " <- Такой пользователь уже зарегистрирован!",
    'user_not' => " <- Такого пользователя не существует!",
    'email_reg' => " <- Такой E-mail уже зарегистрирован!",
    'confirm_not' => " <- Пароли не совпадают!",
    'pass_not' => " <- Неверный пароль!",
    'hash_not' => " <- Ошибка создания хэша пароля!"
);

if (isset($_POST['form'])) {
    $form = htmlspecialchars($_POST['form']);
    if ($form == 'login') { // если данные отправлены из формы входа
        if (isset($_POST['login_entry']) && isset($_POST['password_entry'])) {
            $login = htmlspecialchars($_POST['login_entry']);
            $password = htmlspecialchars($_POST['password_entry']);
            if (isExists('login', $login, $user_db)) { // если пользователь есть в базе
                $user_hash = $user_db->selectRow('login', $login, 'passwd'); // получаем его хэш пароля
                if (password_verify($password, $user_hash)) { // если пароль совпадает с хэшем запускаем сессию
                    session_start();
                    $user = $user_db->select('login', $login);
                    //$_SESSION['login'] = $login;
                    //$_SESSION['name'] = $user_db->selectRow('login', $login, 'name');
                    foreach ($user as $key => $val) {
                        $_SESSION[$key] = $val;
                    }
                    // cookie
                    // TODO: Add remember checkbox
                    //if (isset($_POST['remember']) and $_POST['remember'] == 1) {
                    if (true) {
                        // Сформируем случайную строку для куки (используем функцию generateSalt):
                        $key = generateSalt(); // назовем ее $key
                        // Пишем куки (имя куки, значение, время жизни - сейчас+месяц)
                        setcookie('login', $login, time() + 60 * 60 * 24 * 30); // логин
                        setcookie('key', $key, time() + 60 * 60 * 24 * 30); // случайная строка
                        // Пишем эту же куку в базу данных для данного юзера.
                        $cookie = ['cookie' => $key]; // добавляем cookie в массив
                        $user_db->addRow('login', $login, $cookie); // обновляем в базе
                    }
                    // cookie
                } else {
                    $data['password_entry'] = $errors['pass_not'];
                    $data['result'] = 'error';
                }
            } else {
                $data['login_entry'] = $errors['user_not'];
                $data['result'] = 'error';
            }
        }
    } elseif ($form == 'reg') { // $form == 'reg' // если данные отправлены из формы регистрации
        // проверяем присутствие всех данных из формы
        if (isset($_POST['login']) && isset($_POST['password']) && isset($_POST['confirm']) && isset($_POST['email']) && isset($_POST['name'])) {
            // login
            $login = htmlspecialchars($_POST['login']); // защита от передачи скриптов в запросе
            if (isExists('login', $login, $user_db)) {
                $data['login'] = $errors['user_reg'];
                $data['result'] = 'error';
            }
            // password
            $password = htmlspecialchars($_POST['password']); // защита от передачи скриптов в запросе
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            if (!$pass_hash) {
                $data['password'] = $errors['hash_not'];
                $data['result'] = 'error';
            }
            // confirm
            $confirm = htmlspecialchars($_POST['confirm']); // защита от передачи скриптов в запросе
            if ($password !== $confirm) {
                $data['confirm'] = $errors['confirm_not'];
                $data['result'] = 'error';
            }
            // email
            $email = htmlspecialchars($_POST['email']); // защита от передачи скриптов в запросе
            if (isExists('email', $email, $user_db)) {
                $data['email'] = $errors['email_reg'];
                $data['result'] = 'error';
            }
            // name
            $name = htmlspecialchars($_POST['name']); // защита от передачи скриптов в запросе
            // add user
            if ($data['result'] == 'success') { // если ошибок нет, то добавляем пользователя в базу
                $user_new = new User($login, $pass_hash, $email, $name);
                $user_db->insert($user_new->getUser());
            }
        } else {
            $data['result'] = 'error';
        }
    }
} else {
    $data['result'] = 'error';
}

echo json_encode($data); // отправляем результат
