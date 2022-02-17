<?php

require_once('include/JsonDb.php');
require_once('include/User.php');

try {
    $user_db = new JsonDb("./db/users.json");
} catch (Exception $e) {
    exit($e);
}

$user = $user_db->select('login', 'Vgdn1942');
//$user = $user_db->select('login', 'test123');


//print_r($user);

echo "<br><br>";

foreach ($user as $key2 => $val2) {
        echo "Key: " . $key2 . ", Val: " . $val2;
}

echo "<br><br>";

/*
foreach ($user as $val) {
    if (is_array($val)) {
        return $val;
    } else {
        return $user;
    }
}
*/