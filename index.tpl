<h2>Регистрация</h2>
<form id="reg_form">
    <p>
        <label for="login">Логин:<br></label>
        <input name="login" id="login" type="text" size="32" placeholder="Буквы и/или цифры" minlength="6" maxlength="32"
               pattern="^[a-zA-Z0-9]+$" required>
    </p>
    <p>
        <label for="password">Пароль:<br></label>
        <input name="password" id="password" type="password" size="32"
               placeholder="Буквы и цифры" minlength="6" maxlength="32"
               pattern="^(?=.*\d)(?=.*[a-zA-Z])(?!.*\s).*$" required>
    </p>
    <p>
        <label for="confirm_password">Подверждение пароля:<br></label>
        <input name="confirm" id="confirm_password" type="password" size="32" maxlength="32"
               placeholder="Подтвердите пароль" required>
    </p>
    <p>
        <label for="email">Почта:<br></label>
        <input name="email" id="email" type="text" size="32" placeholder="E-mail" maxlength="32"
               pattern="^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$" required>
    </p>
    <p>
        <label for="name">Имя:<br></label>
        <input name="name" id="name" type="text" size="32" placeholder="Только буквы" minlength="2" maxlength="32"
               pattern="^[a-zA-Zа-яА-Я]+$" required>
    </p>
    <br>
    <button type="submit" name="submit">Зарегистрироваться</button>
</form>

<h2>Вход</h2>
<form id="login_form">
    <p>
        <label for="login_entry">Логин:<br></label>
        <input name="login_entry" id="login_entry" type="text" size="32" placeholder="Логин" minlength="6"
               maxlength="32" pattern="^[a-zA-Z0-9]+$" required>
    </p>
    <p>
        <label for="password_entry">Пароль:<br></label>
        <input name="password_entry" id="password_entry" type="password" size="32"
               placeholder="Формат: символы + цифры" minlength="6" maxlength="32"
               pattern="^(?=.*\d)(?=.*[a-zA-Z])(?!.*\s).*$" required>
    </p>
    <br>
    <button type="submit" name="submit">Войти</button>
</form>
<p></p>

<div id="results">вывод</div>

<script type="text/javascript" src="scripts/ajax_form.js"></script>
