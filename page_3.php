<?php

require_once('include/Template.php');

$tpl_array = array(
    'title' => 'Страница 3'
);

Template::showTemplate('html/head.tpl', $tpl_array);
Template::showTemplate('html/index.tpl', $tpl_array);
Template::showTemplate('html/footer.tpl');