<?php
include_once 'include/Head.php';
Head::setHead("Главная страница", "Главная страница", "scripts/ajax_form.js");
?>
<p></p>
<form method="post" id="ajax_form" action="">
    <label>Логин:<br></label>
    <label>
        <input name="login" type="text" size="24" maxlength="24">
    </label>
    <p></p>
    <label>Пароль:<br></label>
    <label>
        <input name="password" type="password" size="24" maxlength="24">
    </label>
    <br><br>
    <button type="submit" name="refresh">Войти</button>
</form>
<!-- ссылка на регистрацию, ведь как-то же должны гости туда попадать -->
<form action="reg.php" method="post">
    <button type="submit">Зарегистрироваться</button>
</form>
<p></p>

<?php
Head::endHead();
?>
