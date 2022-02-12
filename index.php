<?php
include_once 'include/Head.php';
Head::setHead("Главная страница");
?>

    <h2>Регистрация</h2>
    <form id="reg_form">
        <p>
            <label for="login">Логин:<br></label>
            <input name="login" id="login" type="text" size="24" maxlength="24">
        </p>
        <p>
            <label for="password">Пароль:<br></label>
            <input name="password" id="password" type="password" size="24" maxlength="24">
        </p>
        <p>
            <label for="confirm_password">Подверждение пароля:<br></label>
            <input name="confirm_password" id="confirm_password" type="password" size="24" maxlength="24">
        </p>
        <p>
            <label for="email">E-mail:<br></label>
            <input name="email" id="email" type="email" size="24" maxlength="24">
        </p>
        <p>
            <label for="name">Имя:<br></label>
            <input name="name" id="name" type="text" size="24" maxlength="24">
        </p>
        <br>
        <button type="submit" name="submit">Зарегистрироваться</button>
    </form>

    <h2>Вход</h2>
    <form id="login_form">
        <p>
            <label for="login_entry">Логин:<br></label>
            <input name="login_entry" id="login_entry" type="text" size="24" maxlength="24">
        </p>
        <p>
            <label for="password_entry">Пароль:<br></label>
            <input name="password_entry" id="password_entry" type="password" size="24" maxlength="24">
        </p>
        <br>
        <button type="submit" name="submit">Войти</button>
    </form>
    <p></p>

    <div id="results">вывод</div>

    <script type="text/javascript" src="scripts/ajax_form.js"></script>

<?php
Head::endHead();
?>