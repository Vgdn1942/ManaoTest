<?php

echo "<pre>";
print_r($_POST);
echo "</pre>";

if (isset($_POST["name"]) && isset($_POST["password"]) ) {

    // Формируем массив для JSON ответа
    $result = array(
        'name' => $_POST["name"],
        'password' => $_POST["password"]
    );

    // Переводим массив в JSON
    echo json_encode($result);
}
