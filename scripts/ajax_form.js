$('#login_form').submit(function() {
    $.post(
        'post.php', // адрес обработчика
        $("#login_form").serialize(), // отправляемые данные
        function(msg) { // получен ответ сервера
            $('#results').html(msg);
        }
    );
    return false;
});

/*
function sendAjaxForm(result_form, ajax_form, url) {
    $.ajax({
        url:     url, //url страницы (action_form.php)
        type:     "POST", //метод отправки
        dataType: "html", //формат данных
        data: $("#"+ajax_form).serialize(),  // Сеарилизуем объект
        success: function(response) { //Данные отправлены успешно
            result = $.parseJSON(response);
            $('#result_form').html('Логин: '+result.login+'<br>Пароль: '+result.password);
        },
        error: function(response) { // Данные не отправлены
            $('#result_form').html('Ошибка. Данные не отправлены.');
        }
    });
}
*/