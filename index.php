<?php

require_once('include/Template.php');

session_start();

if (isset($_GET['logout'])) {
    if (!empty($_SESSION['login'])) {
        session_destroy(); //разрушаем сессию для пользователя
    }
    // Удаляем куки авторизации путем установления времени их жизни на текущее время:
    setcookie('login', '', time()); // удаляем логин
    setcookie('key', '', time()); // удаляем ключ
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

if (empty($_SESSION['login'])) {
    if (!empty($_COOKIE['key'])) {
        // Пишем ключ из кук в переменные (для удобства работы):
        $key = $_COOKIE['key']; // ключ из кук (аналог пароля, в базе поле cookie)
        // Формируем и отсылаем запрос к базе:
        try {
            $user_db = new JsonDb("./db/users.json");
        } catch (Exception $e) {
            exit($e);
        }
        // Если база данных вернула не пустой ответ - значит пара логин-ключ от кук подошла...
        if (count($user_db->select('cookie', $key)) !== 0) {
            $user = $user_db->select('cookie', $key);
            // Пишем в сессию :
            foreach ($user as $key => $val) {
                $_SESSION[$key] = $val;
            }
        }
    }
}

$tpl_array = array(
    'title' => 'Главная страница'
);

Template::showTemplate('html/head.tpl', $tpl_array);

if (!empty($_SESSION['login'])) {
    Template::showTemplate('html/index.tpl', $tpl_array);
} else {
    Template::showTemplate('html/reg.tpl');
}

Template::showTemplate('html/footer.tpl');
