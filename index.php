<?php
require_once('include/Template.php');
require_once('include/User.php');

function is_user_logged(): bool {
    return false;
}

$user = new User('Test_login', 'Test_passwd', 'Test_email', 'Test_name');
$str_hello = "Hello, " . $user->getName();

$hello = array(
    'hello' => $str_hello
);

$head_title = array(
    'title' => 'Главная страница'
);

Template::showTemplate('head.tpl', $head_title);

if (is_user_logged()) {
    Template::showTemplate('hello.tpl', $hello);
} else {
    Template::showTemplate('index.tpl');
}

Template::showTemplate('footer.tpl');
