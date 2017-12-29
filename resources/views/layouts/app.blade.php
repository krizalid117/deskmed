<?php

    use \Illuminate\Support\Facades\DB;

//    \Debugbar::disable();

    $sub = null;

    $consulta = "
            select s.id as id_sub
            , s.id_usuario
            , s.id_plan
            , to_char(s.inicio_subscripcion, 'dd-mm-yyyy') as inicio_subscripcion
            , to_char(s.termino_subscripcion, 'dd-mm-yyyy') as termino_subscripcion
            , cast(extract(epoch from s.inicio_subscripcion::timestamp without time zone) as integer) as tstamp_inicio_sub
            , cast(extract(epoch from s.termino_subscripcion::timestamp without time zone) as integer) as tstamp_termino_sub
            , to_char(s.updated_at, 'dd-mm-yyyy HH24:mi:ss') as updated_at
            , cast(extract(epoch from s.updated_at::timestamp without time zone) as integer) as tstamp
            , concat_ws(' ', u.nombres, u.apellidos) as usuario_nombre_completo
            , pl.nombre as nombre_plan
            , pl.precio_mensual::int as precio_mensual_plan
            , sum(pa.total)::int as total_pagos
            , case
                when count(pa) > 0 then
                    json_agg((
                        select to_json(a) from (
                            select pa.total::int
                            , pa.estado
                            , to_char(pa.updated_at, 'dd-mm-yyyy HH24:mi:ss') as updated_at
                        ) a
                    ) order by pa.updated_at desc)
                else '[]'
            end as pagos
            from subscripciones s
            join usuarios u
              on u.id = s.id_usuario
            join planes pl
              on pl.id = s.id_plan
            join pagos pa
              on pa.id_subscripcion = s.id
            where now() between s.inicio_subscripcion and s.termino_subscripcion
            and s.id_usuario = ?
            group by s.id, u.id, pl.id
        ";

    if ($r = DB::select($consulta, [$usuario->id])) {
        if (count($r)> 0) {
            $sub = $r[0];
        }
    }

    $planes = [];

    $consulta = "
        select p.id
        , p.nombre
        , p.precio_mensual
        from planes p
        where p.activo is true
        order by precio_mensual desc
    ";

    if ($rp = DB::select($consulta)) {
        $planes = $rp;
    }

?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Deskmed @yield('title')</title>
    <link rel="stylesheet" href="{{ URL::to('js/bootstrap-3.3.7/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('js/jquery-ui-1.12.1.custom/jquery-ui.css') }}">

    <link rel="stylesheet" href="{{ URL::to('css/variables_app.css') }}?_<?php echo time(); ?>">
    <link rel="stylesheet" href="{{ URL::to('css/main.css') }}?_<?php echo time(); ?>">
    <link rel="stylesheet" href="{{ URL::to('js/select2-4.0.3/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('css/wfmi-style.css') }}">
    <link rel="stylesheet" href="{{ URL::to('css/pretty.css') }}">
    <link rel="stylesheet" href="{{ URL::to('js/DataTables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('js/timepicker/jquery.timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('js/multidatespicker/jquery-ui.multidatespicker.css') }}">

    @yield('stylesheets')
</head>
<body>

<div class="main-container">
    <div class="side-menu">
        @include('includes.menu')
    </div>
    <div class="dm-header">
        @include('includes.header')
    </div>
    <div class="content">
        <div class="container-fluid dm-content">
            @yield('content')
        </div>
    </div>
    <div class="dm-footer">

    </div>
</div>

<script>
    window.Laravel = { csrfToken: '{{ csrf_token() }}' };
</script>

@if(Route::current()->getName() === "user.mainchat")
    <script src="{{ asset('js/app.js') }}"></script>
@endif

<script src="{{ URL::to('js/jquery-3.1.1.js') }}"></script>
<script src="{{ URL::to('js/jquery-ui-1.12.1.custom/jquery-ui.js') }}"></script>
<script src="{{ URL::to('js/select2-4.0.3/dist/js/select2.min.js') }}"></script>
<script src="{{ URL::to('js/select2-4.0.3/dist/js/i18n/es.js') }}"></script>
<script>

    // Change JQueryUI plugin names to fix name collision with Bootstrap.
    $.widget.bridge('uitooltip', $.ui.tooltip);
    $.widget.bridge('uibutton', $.ui.button);

    $.datepicker.regional["es"] = { // Español regional settings
        closeText: "Cerrar", // Display text for close link
        prevText: "Anterior", // Display text for previous month link
        nextText: "Siguiente", // Display text for next month link
        currentText: "Hoy", // Display text for current month link
        monthNames: [ "Enero","Febrero","Marzo","Abril","Mayo","Junio",
            "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre" ], // Names of months for drop-down and formatting
        monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ], // For formatting
        dayNames: [ "Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado" ], // For formatting
        dayNamesShort: [ "Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb" ], // For formatting
        dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá" ], // Column headings for days starting at Sunday
        weekHeader: "Sem", // Column header for week of the year
        dateFormat: "dd-mm-yy", // See format options on parseDate
        firstDay: 1, // The first day of the week, Sun = 0, Mon = 1, ...
        isRTL: false, // True if right-to-left language, false if left-to-right
        showMonthAfterYear: false, // True if the year select precedes month, false for month then year
        yearSuffix: "", // Additional text to append to the year in the month headers
        changeYear: true,
        changeMonth: true
    };

    $.datepicker.setDefaults($.datepicker.regional["es"]);
</script>
<script src="{{ URL::to('js/bootstrap-3.3.7/js/bootstrap.min.js') }}"></script>
<script src="{{ URL::to('js/DataTables/datatables.min.js') }}"></script>
<script src="{{ URL::to('js/floatThead/jquery.floatThead.js') }}"></script>
<script src="{{ URL::to('js/floatThead/jquery.floatThead._.js') }}"></script>
<script src="{{ URL::to('js/timepicker/jquery.timepicker.min.js') }}"></script>
<script src="{{ URL::to('js/jscolor.js') }}"></script>
<script src="{{ URL::to('js/functions.js') }}?_<?php echo time(); ?>"></script>

<script> {{-- Scripts para el menu --}}

    var menuCollapser = $('.side-menu-collapser').find('button');
    var menuContenedorItems = $('.side-menu-item-container');
    var menuLateral = $('.side-menu-minified');
    var profile = $('.header-a-profile');
    var notifications = $('.header-a-notif');

    $(function () {
        setXsClasses();

        menuCollapser.data('collapsed', true);
        profile.data('open', false);
        notifications.data('open', false);

        /* Colapsar y descolapsar menú lateral */
        menuCollapser.click(function () {

            if (menuCollapser.data('collapsed') === true) {
                menuAbrir();
            }
            else {
                menuCerrar();
            }
        });

        /* Colapsar menú lateral cuando se haga click en cualquier otro elemento en el DOM */
        $(document).click(function (e) {
            var $target = $(e.target);

            if (!$target.is('.side-menu') && !$target.parents().is('.side-menu')) {
                menuCerrar();
            }

            if (!$target.is('.profile-menu-p') && !$target.parents().is('.profile-menu-p')) {
                profileCerrar();
            }

            if (!$target.is('.profile-menu-n') && !$target.parents().is('.profile-menu-n')) {
                notifCerrar();
            }
        });

        $(document).on('click', '.deskmed-icon-help', function () {
            mensajes.alerta('<div style="text-align: justify;">' + $(this).attr('title') + '</div>', 'Información');
        });

        $(document).on('click', '.fs-collapsable-title', function () {
            var $this = $(this);
            var icon = $this.find('.ui-icon');
            var fs = $this.closest('.fs-collapsable');
            var content = fs.find('.fs-collapsable-content');

            if (!$this.hasClass("fs-collapsing")) {
                $this.addClass("fs-collapsing");

                if (fs.data("collapsed")) {
                    icon.removeClass('ui-icon-plus');
                    icon.addClass('ui-icon-minus');
                }
                else {
                    icon.removeClass('ui-icon-minus');
                    icon.addClass('ui-icon-plus');
                }

                fs.data("collapsed", !fs.data("collapsed"));

                content.animate({
                    height: "toggle"
                }, 350, function () {
                    $this.removeClass("fs-collapsing");
                });
            }
        });

        /* Click en elementos del menú */
        $('.side-menu-item').click(function (e) {

            if (!$(this).hasClass('side-menu-selected')) {
                $('.side-menu-selected').removeClass('side-menu-selected');

                $(this).addClass('side-menu-selected');
            }

            //Redirecciones
            if ($(this).hasClass('menu-home')) {
                window.location = '{{ route('home') }}';
            }
            else if ($(this).hasClass('menu-doctores')) {
                window.location = '{{ route('paciente.doctores') }}';
            }
            else if ($(this).hasClass('menu-agenda')) {
                window.location = '{{ $usuario->id_tipo_usuario === 2 ? route('doctor.agenda') : ($usuario->id_tipo_usuario === 3 ? route('patient.agenda') : route("home")) }}';
            }
            else if ($(this).hasClass('menu-chat')) {
                window.location = '{{ route('user.mainchat') }}';
            }

        });

        /* Click en opciones de perfil */
        $('.profile-setting').click(function () {
            if ($(this).hasClass('profile-setting-config')) {
                window.location = '{{ route('usuario.profile') }}';
            }
            else if ($(this).hasClass('profile-setting-logout')) {
                window.location = '{{ route('logout') }}';
            }
            else if ($(this).hasClass('profile-setting-career')) {
                window.location = '{{ route('usuario.profesion') }}';
            }
            else if ($(this).hasClass('profile-setting-ficha')) {
                window.location = '{{ route('usuario.ficha') }}';
            }
            else if ($(this).hasClass('profile-setting-sub')) {

                profileCerrar();

                @if(!is_null($sub))
                    {{-- Subscrito --}}


                @else
                    {{-- No subscrito --}}

                    $('<div class="dlg-subscribe">' +
                        '<table class="table table-striped table-hover table-condensed">' +
                            '<thead>' +
                                '<tr>' +
                                    '<th style="text-align: left;">Nombre</th>' +
                                    '<th style="text-align: right; width: 200px;">Precio mensual</th>' +
                                '</tr>' +
                            '</thead>' +
                            '<tbody>' +
                                <?php if (count($planes) > 0) {
                                    foreach ($planes as $plan) { ?>
                                        '<tr>' +
                                            '<td>{{ $plan->nombre }}</td>' +
                                            '<td style="text-align: right;">${{ number_format($plan->precio_mensual, 0, ',', '.') }}</td>' +
                                        '</tr>' +
                                    <?php
                                    }
                                } else { ?>
                                    '<tr>' +
                                        '<td colspan="2">No hay planes.</td>' +
                                    '</tr>' +
                                <?php } ?>
                            '</tbody>' +
                        '</table>' +
                        '<hr>' +
                        'Para subscribirte a Deskmed y empezar a configurar tu horario y a tener consultas médicas online, debes hacer una transferencia bancaria a: <br><br>' +
                        '<span class="bold">Cuenta corriente: </span> 61874566<br>' +
                        '<span class="bold">Banco: </span> BCI<br>' +
                        '<span class="bold">RUT: </span> 18.244.205-4<br>' +
                        '<span class="bold">Nombre: </span> Patricio Fernando Zúñiga González<br>' +
                        '<span class="bold">Correo electrónico: </span> patricio.zunigag@gmail.com' +
                        '<br><br>' +
                        'Y envía un e-mail a <span class="bold">patricio.zunigag@gmail.com</span> adjuntando el voucher o comprobante e indicando el correo electrónico con el que estás registrado, así como el plan al cual deseas subscribirte. ¡En las próximas 48 horas hábiles se validará tu pago y ya podrás disfrutar al 100% de Deskmed!' +
                        '<br><br>' +
                        '<sub>Los precios listados incluyen IVA.</sub>' +
                    '</div>').dialog({
                        title: "¡Subscríbete a Deskmed!",
                        classes: { 'ui-dialog': 'dialog-responsive' },
                        width: 500,
                        resizable: false,
                        draggable: true,
                        autoOpen: true,
                        modal: true,
                        escapeOnClose: true,
                        close: function () {
                            $('.dlg-subscribe').dialog('destroy').remove();
                        },
                        buttons: [
                            {
                                text: "Cerrar",
                                'class': 'btn',
                                click: function () {
                                    $('.dlg-subscribe').dialog("close");
                                }
                            }
                        ]
                    });

                @endif
            }
            @if ($usuario->id_tipo_usuario === 1)
                else if ($(this).hasClass('profile-setting-validations')) {
                    window.location = '{{ route('admin.validations') }}';
                }
                else if ($(this).hasClass('profile-setting-subs')) {
                    window.location = '{{ route('admin.subs') }}';
                }
            @endif
        });

        /* Click en foto de perfil */
        profile.click(function (e) {
            e.preventDefault();

            if (profile.data('open') === false) {
                profileAbrir();
            }
            else {
                profileCerrar();
            }
        });

        /* Click en campana de notificaciones */
        notifications.click(function (e) {
            e.preventDefault();

            if (notifications.data('open') === false) {
                notifAbrir();
            }
            else {
                notifCerrar();
            }
        });

        $('.txt-header-search').keydown(function (e) {
            if (e.which === 13) {
                var keyword = $.trim($(this).val());

                if (keyword !== "") {
                    buscar(keyword);
                }
            }
        });

        $('#btn-search').click(function () {
            var keyword = $.trim($('.txt-header-search').val());

            if (keyword !== "") {
                buscar(keyword);
            }
        });

        $(window).resize(function () {
            setXsClasses();
        });

        setInterval(reloadNotifications, 30 * 1000);
    });

    function setXsClasses() {
        if (document.documentElement.clientWidth <= 767) {
            if (menuCollapser.data('collapsed') !== true) {
                menuCerrar();
            }

            menuContenedorItems.addClass('hidden');
            menuLateral.addClass('side-menu-xs');
        }
        else {
            menuContenedorItems.removeClass('hidden');
            menuLateral.removeClass('side-menu-xs');
        }
    }

    function menuAbrir(callback) {
        if (!menuCollapser.hasClass('menu-icon-disable') && menuCollapser.data('collapsed') === true) {
            menuCollapser.addClass('menu-icon-disable');

            menuCollapser.data('collapsed', false);

            menuCollapser.addClass('is-active');

            if (document.documentElement.clientWidth <= 767) {
                menuContenedorItems.removeClass('hidden');
                menuLateral.removeClass('side-menu-xs');
            }

            menuLateral.css('width', '300px');
            $('.side-menu-item').removeAttr('title');

            setTimeout(function () {
                $('.content-menu').fadeIn(150);
            }, 350);

            setTimeout(function () {
                menuCollapser.removeClass('menu-icon-disable');

                if (callback) {
                    callback();
                }
            }, 400);
        }
    }

    function menuCerrar(callback) {
        if (!menuCollapser.hasClass('menu-icon-disable') && menuCollapser.data('collapsed') === false) {
            menuCollapser.addClass('menu-icon-disable');

            menuCollapser.data('collapsed', true);

            menuCollapser.removeClass('is-active');

            menuLateral.css('width', '60px');

            $('.img-menu').not('.img-menu-toggle').each(function () {
                $(this).closest('.side-menu-item').attr('title', $(this).data('title'));
            });

            $('.content-menu').fadeOut(10);

            setTimeout(function () {
                menuCollapser.removeClass('menu-icon-disable');

                if (document.documentElement.clientWidth <= 767) {
                    menuContenedorItems.addClass('hidden');
                    menuLateral.addClass('side-menu-xs');
                }

                if (callback) {
                    callback();
                }
            }, 400);
        }
    }

    function profileAbrir(callback) {
        if (!profile.hasClass('profile-disable') && profile.data('open') === false) {
            profile.addClass('profile-disable');

            profile.data('open', true);

            //abrir ventana

            $('.profile-menu-p').fadeIn(200, function () {
                profile.removeClass('profile-disable');

                if (callback) {
                    callback();
                }
            });
        }
    }

    function profileCerrar(callback) {

        if (!profile.hasClass('profile-disable') && profile.data('open') === true) {
            profile.addClass('profile-disable');

            profile.data('open', false);

            //cerrar ventana

            $('.profile-menu-p').fadeOut(200, function () {
                profile.removeClass('profile-disable');

                if (callback) {
                    callback();
                }
            });
        }
    }

    function notifAbrir(callback) {
        if (!notifications.hasClass('profile-disable') && notifications.data('open') === false) {
            notifications.addClass('profile-disable');

            notifications.data('open', true);

            //abrir ventana

            $('.profile-menu-n').fadeIn(200, function () {
                notifications.removeClass('profile-disable');

                if (callback) {
                    callback();
                }
            });
        }
    }

    function notifCerrar(callback) {

        if (!notifications.hasClass('profile-disable') && notifications.data('open') === true) {
            notifications.addClass('profile-disable');

            notifications.data('open', false);

            //cerrar ventana

            $('.profile-menu-n').fadeOut(200, function () {
                notifications.removeClass('profile-disable');

                if (callback) {
                    callback();
                }
            });
        }
    }

    function buscar(keyword) {

        var kword = keyword.replace(/[\.\+,\-_!\|°"#\$%&/\^\(\)=\?¿¡\*]/g, "");

        if (kword.length > 0) {
            window.location = '/search/' + kword;
        }
        else {
            alert("lala");
        }
    }

    function reloadNotifications() {
        $.get('{{ route('usuario.getnotification') }}', { _token: '{{ csrf_token() }}'}, function (data) {
            $('.profile-window-n').html(data);

            var text = "";

            if ($('#unread-notif-count').length && $('#unread-notif-count').val() !== "0") {
                text = $('#unread-notif-count').val();
            }

            $('.header-notifications-count').text(text);
        });
    }

    function checkVerificationRequest(id, n) {
        sendPost('{{ route('usuario.getverificationresponse') }}', {
            _token: '{{ csrf_token() }}',
            id: id,
            n: n
        }, function (response) {
            mensajes.alerta(response.mensaje, "Solicitud de verificación", function () {
                reloadNotifications();
            });
        });
    }

    function verHora(esReserva, id, n) { //esReserva = true, false si es cancelación
        sendPost('{{ route('user.getinfohora') }}', {
            _token: '{{ csrf_token() }}',
            id: id,
            n: n
        }, function (res) {
            reloadNotifications();

            $('<div id="dlg-notif-hora">' +
                (esReserva ? '¡Han reservado una de tus horas!' : 'Una de tus horas ha sido cancelada.<br><br>') +
                '<div class="col-sm-6 form-group">' +
                    '<label for="notif-hora-nombre" class="form-label">Hora</label>' +
                    '<input type="text" id="notif-hora-nombre" class="form-control" value="' + res.hora.nombre + '" readonly>' +
                '</div>' +
                '<div class="col-sm-6 form-group">' +
                    '<label for="notif-hora-fecha" class="form-label">Fecha</label>' +
                    '<input type="text" id="notif-hora-fecha" class="form-control" value="' + res.hora.fecha + '" readonly>' +
                '</div>' +
                '<div class="col-sm-6 form-group">' +
                    '<label for="notif-hora-inicio" class="form-label">Hora inicio</label>' +
                    '<input type="text" id="notif-hora-inicio" class="form-control" value="' + res.hora.hora_inicio + '" readonly>' +
                '</div>' +
                '<div class="col-sm-6 form-group">' +
                    '<label for="notif-hora-termino" class="form-label">Hora término</label>' +
                    '<input type="text" id="notif-hora-termino" class="form-control" value="' + res.hora.hora_termino + '" readonly>' +
                '</div>' +
                '<div class="col-sm-12">' +
                    'Reservado por: <br>' +
                    '<a href="/patients/' + res.usuario.id + '/record">' +
                        '<img style="width: 40px; height: 40px;" class="img-circle" src="' + res.usuario.imgProfile + '"> ' + res.usuario.nombres + ' ' + res.usuario.apellidos +
                    '</a>' +
                '</div>' +
            '</div>').dialog({
                title: esReserva ? 'Reserva de hora' : 'Cancelación de reserva',
                width: 400,
                classes: { 'ui-dialog': 'dialog-responsive' },
                resizable: false,
                modal: true,
                autoOpen: true,
                close: function () {
                    $(this).dialog('destroy').remove();
                },
                closeOnEscape: false,
                buttons: [
                    {
                        text: "Cerrar",
                        'class': 'btn',
                        click: function () {
                            $(this).dialog("close");
                        }
                    }
                ]
            });
        });
    }
</script>

@yield('scripts')

</body>
</html>