$('#login_form').submit(function() {
    sendForm('#login_form', 'login');
    return false;
});

$('#reg_form').submit(function() {
    sendForm('#reg_form', 'reg');
    return false;
});

function sendForm (form, name) {
    $.post(
        'post.php', // адрес обработчика
        $(form).serialize() + '&form=' + name, // отправляемые данные
        function(msg) { // получен ответ сервера
            let errorList = JSON.parse(msg);
            for (let i = 0; i < errorList.length; i++) {
                let id = name + errorList[i]['0']; // создаём уникальный id
                if (document.getElementById(id)) { // удаляем элемент, если он уже был отображён
                    document.getElementById(id).remove();
                }
                if (errorList[i]['1'] !== 'ok') { // если есть ошибки выводим их напротив соответствующего поля
                    if (!document.getElementById(id)) { // если ошибка уже отображена
                        showError(errorList[i]['0'], errorList[i]['1'], id);
                        //alert("List: " + errorList[i]['0'] + "ID: " + id);
                    }
                }
            }
            $('#results').html(errorList[0] + errorList[1]);
        }
    );
}

// функция сообщения об ошибке
// В этой функции сразу создается элемент span с ошибкой errorMessage,
// которому присваивается имя класса errorMsg для установки стиля сообщений об ошибках.
// Затем добавляется этот элемент внутрь тэга label.
// field - поле, возле которого нужно вывести сообщение об ошибке
// errorMessage - сообщение об ошибке

let errorSpan;

function showError (field, message, id) {

    errorSpan = document.createElement("span");
    errorSpan.setAttribute("id", id);
    const errorMessage = document.createTextNode(message);

    errorSpan.appendChild(errorMessage);
    errorSpan.className = "errorMsg";

    let fieldLabel = document.getElementById(field).previousSibling;
    while (fieldLabel.nodeName.toLowerCase() !== "label") {
        fieldLabel = fieldLabel.previousSibling;
    }
    fieldLabel.appendChild(errorSpan);
}
