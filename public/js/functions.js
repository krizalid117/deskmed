function sendPost(__url, __opt, __func, __loadimg) {
    mensajes.loading_open(__loadimg);

    $.post(__url, __opt, function (response) {
        if (!response.error) {
            __func(response);
        }
        else {
            var errorMsj = response.hasOwnProperty('mensaje') ? response.mensaje : 'Hubo un error. Por favor, intente de nuevo más tarde.';

            mensajes.alerta(errorMsj);
        }
    }, 'json')
        .fail(function (res) {
            if (res.status === 422) {
                var msg = 'Por favor, corrija los siguiente errores antes de continuar: <ul class="post-error-list" style="padding-left: 30px;">';

                $.each(res.responseJSON, function (key, value) {
                    for (var i = 0; i < value.length; i++) {
                        msg += '<li class="post-error-item">' + value[i] + '</li>';
                    }
                });

                msg += '</ul>';

                mensajes.alerta(msg);
            }
            else {
                mensajes.alerta('Ha ocurrido un error inesperado. Por favor, intente de nuevo más tarde.');
            }
        })
        .always(function () {
            mensajes.loading_close();
        });
}

var mensajes = {
    alerta: function (msg, title, func) {
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
            closeOnEscape: false,
            buttons: [
                {
                    text: "Aceptar",
                    'class': 'btn btn-primary',
                    click: function () {
                        if (func) {
                            func();
                        }

                        $(this).dialog('close');
                    }
                }
            ]
        });
    },
    loading_open: function (__loadimg) {
        __loadimg = __loadimg || 'img/loading.gif';

        $('body').append('<div id="loading-div" style="height: 100%; width: 100%; display: flex; justify-content: center; align-items: center; position: fixed; top: 0; left: 0; background-color: black; opacity: .6; z-index: 1000;">' +
            '<img src="' + __loadimg + '" style="width: 100px; height: 100px; user-select: none; -webkit-user-select: none; -moz-user-select: none;">' +
            '</div>');
    },
    loading_close: function () {
        $('#loading-div').remove();
    }
};