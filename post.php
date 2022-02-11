<?php

// проверяем, что что это ajax запрос
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    // проверяем, что получены логи и пароль
    if (isset($_POST['login']) && isset($_POST['password']) ) {
        // формируем массив
        $result = array(
            'login' => $_POST['login'],
            'password' => $_POST['password']
        );
        // отправляем ответ
        echo json_encode($result); // json
        echo "<br>";
        print_r($result); // массив
    }
} else { // если это не ajax
    exit;
}
