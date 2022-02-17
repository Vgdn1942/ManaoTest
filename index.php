<?php
require_once('include/Template.php');
require_once('include/User.php');

session_start();

if (isset($_GET['logout'])) {
    $_SESSION = [];
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

$tpl_array = array(
    'title' => 'Главная страница',
    'hello' => "Hello, " . $_SESSION['name']
);

Template::showTemplate('html/head.tpl', $tpl_array);

if (isset($_SESSION['login'])) {
    Template::showTemplate('html/hello.tpl', $tpl_array);
} else {
    Template::showTemplate('html/index.tpl');
}

Template::showTemplate('html/footer.tpl');
