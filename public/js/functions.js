function sendPost(__url, __obj, __func, __loadimg) {
    __loadimg = __loadimg || 'img/loading.gif';

    $('body').append('<div id="loading-div" style="height: 100%; width: 100%; display: flex; justify-content: center; align-items: center; position: fixed; top: 0; left: 0; background-color: black; opacity: .6; z-index: 1000;">' +
        '<img src="' + __loadimg + '" style="width: 100px; height: 100px; user-select: none; -webkit-user-select: none; -moz-user-select: none;">' +
    '</div>');

    $.post(__url, __obj, function (response) {
        if (!response.error) {
            __func(response);
        }
        else {
            alerta('An error has occured');
        }
    }, 'json')
        .fail(function (res) {
            if (res.status === 422) {
                var msg = 'Por favor, corrija los siguiente errores antes de continuar: <ul>';

                $.each(res.responseJSON, function (key, value) {
                    for (var i = 0; i < value.length; i++) {
                        msg += '<li>' + value[i] + '</li>';
                    }
                });

                msg += '</ul>';

                alerta(msg);
            }
            else {
                alerta('Ha ocurrido un error inesperado. Por favor, intente de nuevo más tarde.');
            }
        })
        .always(function () {
            $('#loading-div').remove();
        });
}

function alerta(msg, title, func) {
    title = title || "Alerta";

    $('<div class="div-alerta">' + msg + '</div>').dialog({
        title: title,
        width: 350,
        resizable: false,
        modal: true,
        autoOpen: true,
        close: function () {
            $(this).dialog('destroy').remove();
        },
        buttons: [
            {
                text: "Cerrar",
                'class': 'btn',
                click: function () {
                    if (func) {
                        func();
                    }

                    $(this).dialog('close');
                }
            }
        ]
    });
}