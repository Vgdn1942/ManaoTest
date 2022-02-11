<?php
include_once 'include/Head.php';
Head::setHead("Регистрация", "Регистрация", "scripts/ajax_form.js");
?>
<form method="post" id="ajax_form" action="">
    <p>
        <label>Ваш логин:<br></label>
        <label>
            <input name="login" type="text" size="24" maxlength="24">
        </label>
    </p>
    <p>
        <label>Ваш пароль:<br></label>
        <label>
            <input name="password" type="password" size="24" maxlength="24">
        </label>
    </p>
    <br>
    <button type="submit" id="btn_submit" name="submit">Зарегистрироваться</button>
</form>
<form action="">
    <button type="submit" name="back">Назад</button>
</form>
<?php
if (isset($_GET["back"])) {
    header("Location: index.php");
}
Head::endHead();
?>
