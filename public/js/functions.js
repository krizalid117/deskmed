$.fn.removeClassPrefix = function(prefix) {
    this.each(function(i, el) {
        var classes = el.className.split(" ").filter(function(c) {
            return c.lastIndexOf(prefix, 0) !== 0;
        });
        el.className = $.trim(classes.join(" "));
    });
    return this;
};

function sendPost(__url, __opt, __func, __loadimg) {
    mensajes.loading_open(__loadimg);

    $.post(__url, __opt, function (response) {
        try {
            if (!response.error) {
                __func(response);
            }
            else {
                var errorMsj = response.hasOwnProperty('mensaje') ? response.mensaje : 'Hubo un error. Por favor, intente de nuevo más tarde.';

                mensajes.alerta(errorMsj);
            }
        }
        catch (ex) {
            mensajes.alerta("Error al ejecutar la acción.");
        }
    }, 'json')
        .fail(function (res) {
            if (res.status === 422) {

                var msg = '<p style="text-align: left;">Por favor, corrija los siguiente errores antes de continuar:</p> <ul class="post-error-list" style="padding-left: 30px;">';

                $.each(res.responseJSON, function (key, value) {
                    for (var i = 0; i < value.length; i++) {
                        msg += '<li class="post-error-item">' + value[i] + '</li>';

                        var elem = $('.form-group[inp-name="' + key + '"]');

                        if (elem.length) {
                            elem.removeClassPrefix('has-').addClass('has-error');
                        }
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
                        $(this).dialog('close');

                        if (func) {
                            func();
                        }
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

function clearRut(rut) { //Limpia un rut de cualquier caracter que no sea k, K ni dígitos y ceros a la izquierda (borra también puntos y guiones)
    return $.trim(rut.replace(/[^k0-9]/ig, "").replace(/^0*/, "").toUpperCase());
}

function formatearRut(rut) {
    var sRut1 = clearRut(rut);
    var nPos = 0;
    var sInvertido = "";
    var sRut = "";

    for (var i = sRut1.length - 1; i >= 0; i--) {
        sInvertido += sRut1.charAt(i);

        if (i == sRut1.length - 1) {
            sInvertido += "-";
        }
        else if (nPos == 3) {
            sInvertido += ".";
            nPos = 0;
        }

        nPos++;
    }

    for (var j = sInvertido.length - 1; j >= 0; j--) {
        if (sInvertido.charAt(sInvertido.length - 1) != ".") {
            sRut += sInvertido.charAt(j);
        }
        else if (j != sInvertido.length - 1) {
            sRut += sInvertido.charAt(j);
        }
    }

    //Pasamos al campo el valor formateado
    return  sRut.toUpperCase();
}

function validarRut(rutCompleto) { //debe estar en formato: xx.xxx.xxx-x

    if (!/^[0-9]{1,3}\.[0-9]{3}\.[0-9]{3}-[Kk0-9]$/.test(rutCompleto)) {
        return false;
    }

    rutCompleto = clearRut(rutCompleto);

    var digv = rutCompleto.charAt(rutCompleto.length - 1);
    var rut = rutCompleto.substr(0, rutCompleto.length - 1);

    if (digv === 'K') {
        digv = 'k';
    }

    function dv(T) {
        var M=0,S=1;
        for(;T;T=Math.floor(T/10))
            S=(S+T%10*(9-M++%6))%11;
        return S?S-1:'k';
    }

    return dv(rut) == digv;
}

/********************* Funciones JQuery *********************/

/*  Valida inputs donde van ruts
    @param doValidation: función que devuelve true si se quiere realizar o no la validación. Útil para cuando en el mismo input pueden ir otros tipo de identificadores aparte de rut
 */
$.fn.RutValidation = function (doValidation) {

    doValidation = doValidation || function () { return true; };

    this.blur(function () {
        var $this = $(this);

        if (doValidation.call(this) === true) {
            var rutFormateado = formatearRut($this.val());

            if (!validarRut(rutFormateado) && $this.val() !== '') {
                mensajes.alerta('El RUT ingresado no es válido.', 'RUT inválido', function () {
                    $this.focus();
                });
            }
            else {
                $this.val(rutFormateado);
            }
        }
    });
};