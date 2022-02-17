<?php
require_once('include/Template.php');
require_once('include/User.php');

session_start();

if (isset($_GET['logout'])) {
    unset($_SESSION['login']);
    unset($_SESSION['name']);
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

function is_user_logged(): bool {
    return isset($_SESSION['login']);
}

$tpl_array = array(
    'title' => 'Главная страница',
    'hello' => "Hello, " . $_SESSION['name']
);

Template::showTemplate('html/head.tpl', $tpl_array);

if (is_user_logged()) {
    Template::showTemplate('html/hello.tpl', $tpl_array);
} else {
    Template::showTemplate('html/index.tpl');
}

Template::showTemplate('html/footer.tpl');
