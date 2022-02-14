<?php
require_once('include/Template.php');

$head_title = array(
    'title' => 'Главная страница'
);

Template::showTemplate('head.tpl', $head_title);
//if (is_user_logged()) {
    Template::showTemplate('index.tpl');
//} else {
//    Template::showTemplate('index.tpl');
//}
Template::showTemplate('footer.tpl');
