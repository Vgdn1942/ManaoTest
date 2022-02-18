$('#login_form').submit(function () {
    sendForm('#login_form', 'login');
    return false;
});

$('#reg_form').submit(function () {
    sendForm('#reg_form', 'reg');
    return false;
});

function sendForm(form, name) {
    $.post(
        'post.php', // адрес обработчика
        $(form).serialize() + '&form=' + name, // отправляемые данные
        function (msg) { // получен ответ сервера
            $('.errorMsg').remove(); // удаляем все ошибки в форме
            let data = JSON.parse(msg); // парсим ответ
            if (data['result'] !== 'success') { // если есть ошибки
                for (const errorField in data) { // проходим в цикле по ошибкам
                    if (errorField === 'result') { // пропускаем data[result]
                        continue;
                    }
                    showError(errorField, data[errorField]); // показываем ошибки
                }
            }
            //$('#results').html(msg);
            if (data['result'] === 'success') { // если ошибок нет
                $(form).trigger('reset'); // очищаем форму в случае успеха
                if (name === 'login') {
                    location.href = "index.php";
                } else if (name === 'reg') {
                    alert("Регистрация прошла успешно!" + "\n" + "Теперь вы можете выполнить вход.")
                }
            }
        }
    );
}

// функция сообщения об ошибке
// В этой функции сразу создается элемент span с ошибкой message,
// которому присваивается имя класса errorMsg для установки стиля сообщений об ошибках.
// Затем добавляется этот элемент внутрь тэга label.
// field - поле, возле которого нужно вывести сообщение об ошибке
// message - сообщение об ошибке

function showError(field, message) {
    let errorSpan = document.createElement("span");
    const errorMessage = document.createTextNode(message);

    errorSpan.appendChild(errorMessage);
    errorSpan.className = "errorMsg";

    let fieldLabel = document.getElementById(field).previousSibling;
    while (fieldLabel.nodeName.toLowerCase() !== "label") {
        fieldLabel = fieldLabel.previousSibling;
    }
    fieldLabel.appendChild(errorSpan);
}
