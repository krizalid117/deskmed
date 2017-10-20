<?php
    use \Illuminate\Support\Facades\Auth;

    $usuario = Auth::user()["attributes"];
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Deskmed @yield('title')</title>
    <link rel="stylesheet" href="{{ URL::to('js/bootstrap-3.3.7/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('js/jquery-ui-1.12.1.custom/jquery-ui.min.css') }}">

    <link rel="stylesheet" href="{{ URL::to('css/variables_app.css') }}?_<?php echo time(); ?>">
    <link rel="stylesheet" href="{{ URL::to('css/main.css') }}?_<?php echo time(); ?>">
    <link rel="stylesheet" href="{{ URL::to('js/select2-4.0.3/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('css/wfmi-style.css') }}">

    <style>

    </style>

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
        <div class="dm-footer">

        </div>
    </div>
</div>

<script src="{{ URL::to('js/jquery-3.1.1.js') }}"></script>
<script src="{{ URL::to('js/jquery-ui-1.12.1.custom/jquery-ui.min.js') }}"></script>
<script src="{{ URL::to('js/select2-4.0.3/dist/js/select2.min.js') }}"></script>
<script src="{{ URL::to('js/select2-4.0.3/dist/js/i18n/es.js') }}"></script>
<script>
    // Change JQueryUI plugin names to fix name collision with Bootstrap.
    $.widget.bridge('uitooltip', $.ui.tooltip);
    $.widget.bridge('uibutton', $.ui.button);
</script>
<script src="{{ URL::to('js/bootstrap-3.3.7/js/bootstrap.min.js') }}"></script>
<script src="{{ URL::to('js/functions.js') }}?_<?php echo time(); ?>"></script>

<script> {{-- Scripts para el menu --}}
    var menuCollapser = $('.side-menu-collapser').find('.img-menu');

    $(function () {
        menuCollapser.data('collapsed', true);

        menuCollapser.click(function () {

            if (menuCollapser.data('collapsed') === true) {
                menuAbrir();
            }
            else {
                menuCerrar();
            }

        });

        $(document).click(function (e) {
            var target = e.target;

            if (!$(target).is('.side-menu') && !$(target).parents().is('.side-menu')) {
                menuCerrar();
            }
        });

        $('.img-menu').not('.img-menu-toggle').closest('.side-menu-item').click(function () {
            if (!$(this).hasClass('side-menu-selected')) {
                $('.side-menu-selected').removeClass('side-menu-selected');

                $(this).addClass('side-menu-selected');
            }
        });
    });

    function menuAbrir(callback) {
        if (!menuCollapser.hasClass('menu-icon-disable') && menuCollapser.data('collapsed') === true) {
            menuCollapser.addClass('menu-icon-disable');

            menuCollapser.data('collapsed', false);

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
</script>

@yield('scripts')

</body>
</html>