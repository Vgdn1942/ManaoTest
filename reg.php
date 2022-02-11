<?php
include_once 'include/Head.php';
Head::setHead("Регистрация");
?>
<form id="reg_form">
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
    <p>
        <label>Подверждение пароля:<br></label>
        <label>
            <input name="confirm_password" type="password" size="24" maxlength="24">
        </label>
    </p>
    <p>
        <label>E-mail:<br></label>
        <label>
            <input name="email" type="text" size="24" maxlength="24">
        </label>
    </p>
    <p>
        <label>Имя:<br></label>
        <label>
            <input name="name" type="text" size="24" maxlength="24">
        </label>
    </p>
    <br>
    <button type="submit" id="reg_submit" name="submit">Зарегистрироваться</button>
</form>
<form action="">
    <button type="submit" name="back">Назад</button>
</form>

<script type="text/javascript" src="scripts/ajax_form.js"></script>

<?php
if (isset($_GET["back"])) {
    header("Location: index.php");
}
Head::endHead();
?>
