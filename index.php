<?php
require_once('include/Template.php');
require_once('include/User.php');

if (isset($_GET["logout"])) {
    if (isset($_SESSION['login'])) {
        unset($_SESSION['login']);
    }
    header('Location: /');
    exit();
}

function is_user_logged(): bool {
    session_start();
    return isset($_SESSION['login']);
    //return true;
}

$user = new User('Test_login', 'Test_passwd', 'Test_email', 'Test_name');

$tpl_array = array(
    'title' => 'Главная страница',
    'hello' => "Hello, " . $user->getName()
);

Template::showTemplate('head.tpl', $tpl_array);

if (is_user_logged()) {
    Template::showTemplate('hello.tpl', $tpl_array);
} else {
    Template::showTemplate('index.tpl');
}

Template::showTemplate('footer.tpl');
