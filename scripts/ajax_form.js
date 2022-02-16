$('#login_form').submit(function () {
    sendForm('#login_form', 'login');
    return false;
});

$('#reg_form').submit(function () {
    sendForm('#reg_form', 'reg');
    return false;
});

function sendForm(form, name) {
    // todo remove debug
    //alert($(form).serialize() + '&form=' + name);
    $.post(
        'post.php', // адрес обработчика
        $(form).serialize() + '&form=' + name, // отправляемые данные
        function (msg) { // получен ответ сервера
            let data = JSON.parse(msg);
            for (const error in data) {
                if (error === 'result') {
                    continue;
                }
                //alert("Error: " + error + "; error_data: " + data[error]);
                showError(error, data[error]);
            }
            // result => error; login_entry => "текст ошибка"
            /*
            let errorList = JSON.parse(msg); // парсим ответ
            //alert(errorList[errorList.length - 1]['result']);
            for (let i = 0; i < errorList.length; i++) { // проходим в цикле по ошибкам
                let id = name + errorList[i]['0']; // создаём уникальный id
                if (document.getElementById(id)) { // удаляем элемент, если он уже был отображён
                    document.getElementById(id).remove();
                }
                if (errorList[i]['1'] !== 'ok') { // если есть ошибки выводим их напротив соответствующего поля
                    if (!document.getElementById(id)) { // если ошибка ещё не отображена
                        //alert("1: " + errorList[i]['0'] + "; 2: " + errorList[i]['1'] + "; id: " + id);
                        showError(errorList[i]['0'], errorList[i]['1'], id);
                    }
                }
            }
             */
            $('#results').html(msg);
            if (name === 'login' && data['result'] === 'success') {
                location.href = "index.php";
            }
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

function showError(field, message) {

    errorSpan = document.createElement("span");
    //errorSpan.setAttribute("id", id);
    const errorMessage = document.createTextNode(message);

    errorSpan.appendChild(errorMessage);
    errorSpan.className = "errorMsg";

    let fieldLabel = document.getElementById(field).previousSibling;
    while (fieldLabel.nodeName.toLowerCase() !== "label") {
        fieldLabel = fieldLabel.previousSibling;
    }
    fieldLabel.appendChild(errorSpan);
}
