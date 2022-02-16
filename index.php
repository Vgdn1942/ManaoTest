<?php
require_once('include/Template.php');
require_once('include/User.php');

session_start();

if (isset($_GET["logout"])) {
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
/*
if (is_user_logged()) {
    $tpl_array = array(
        'title' => 'Главная страница',
        'hello' => "Hello, " . $_SESSION['name']
    );
    Template::showTemplate('head.tpl', $tpl_array);

} else {
    $tpl_array = array(
        'title' => 'Главная страница'
    );
    Template::showTemplate('head.tpl', $tpl_array);

}
*/
Template::showTemplate('head.tpl', $tpl_array);

if (is_user_logged()) {
    Template::showTemplate('hello.tpl', $tpl_array);
} else {
    Template::showTemplate('index.tpl');
}

Template::showTemplate('footer.tpl');
