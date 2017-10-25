<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Deskmed @yield('title')</title>
    <link rel="stylesheet" href="{{ URL::to('js/bootstrap-3.3.7/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('js/jquery-ui-1.12.1.custom/jquery-ui.css') }}">

    <link rel="stylesheet" href="{{ URL::to('css/variables_app.css') }}?_<?php echo time(); ?>">
    <link rel="stylesheet" href="{{ URL::to('css/main.css') }}?_<?php echo time(); ?>">
    <link rel="stylesheet" href="{{ URL::to('js/select2-4.0.3/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('css/wfmi-style.css') }}">

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
<script src="{{ URL::to('js/functions.js') }}?_<?php echo time(); ?>"></script>

<script> {{-- Scripts para el menu --}}

    var menuCollapser = $('.side-menu-collapser').find('button');
    var profile = $('.header-a-profile');

    $(function () {
        menuCollapser.data('collapsed', true);
        profile.data('open', false);

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

            if (!$target.is('.profile-menu') && !$target.parents().is('.profile-menu')) {
                profileCerrar();
            }
        });

        /* Click en elementos del menú */
        $('.side-menu-item').click(function (e) {

            if (!$(this).hasClass('side-menu-selected')) {
                $('.side-menu-selected').removeClass('side-menu-selected');

                $(this).addClass('side-menu-selected');
            }

            //Redirecciones
            if (!$(this).hasClass('menu-chat')) {
                if ($(this).hasClass('menu-home')) {
                    window.location = '{{ route('home') }}';
                }
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
    });

    function menuAbrir(callback) {
        if (!menuCollapser.hasClass('menu-icon-disable') && menuCollapser.data('collapsed') === true) {
            menuCollapser.addClass('menu-icon-disable');

            menuCollapser.data('collapsed', false);

            menuCollapser.addClass('is-active');
            $('.side-menu-minified').css('width', '300px');
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
            $('.side-menu-minified').css('width', '60px');

            $('.img-menu').not('.img-menu-toggle').each(function () {
                $(this).closest('.side-menu-item').attr('title', $(this).data('title'));
            });

            $('.content-menu').fadeOut(10);

            setTimeout(function () {
                menuCollapser.removeClass('menu-icon-disable');

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

            $('.profile-menu').fadeIn(200, function () {
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

            $('.profile-menu').fadeOut(200, function () {
                profile.removeClass('profile-disable');

                if (callback) {
                    callback();
                }
            });
        }
    }
</script>

@yield('scripts')

</body>
</html>