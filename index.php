<?php
include_once 'include/Head.php';
Head::setHead("Вход");
?>

<form id="login_form">
    <p>
    <label>Логин:<br></label>
    <label>
        <input name="login" type="text" size="24" maxlength="24">
    </label>
    </p>
    <p>
    <label>Пароль:<br></label>
    <label>
        <input name="password" type="password" size="24" maxlength="24">
    </label>
    </p>
    <br>
    <button type="submit" id="login_submit" name="refresh">Войти</button>
</form>
<!-- ссылка на регистрацию, ведь как-то же должны гости туда попадать -->
<form action="reg.php" method="post">
    <button type="submit">Зарегистрироваться</button>
</form>
<p></p>

<div id="results">вывод</div>

<script type="text/javascript" src="scripts/ajax_form.js"></script>

<?php
Head::endHead();
?>