<?php
    use \Illuminate\Support\Facades\Auth;

    $usuario = Auth::user();
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
    <div class="content">
        <div class="container-fluid">
            @yield('content')
        </div>
        <div class="footer">
            <div>Icons made by <a href="https://www.flaticon.com/authors/smashicons" title="Smashicons">Smashicons</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div>
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
    $(function () {
        var menuCollapser = $('.side-menu-collapser').find('.img-menu');

        menuCollapser.data('collapsed', true);

        menuCollapser.click(function () {
            if (!menuCollapser.hasClass('menu-icon-disable')) {

                var fadeTimeMs = 150;

                menuCollapser.addClass('menu-icon-disable');

                if (menuCollapser.data('collapsed') === true) {
                    menuCollapser.data('collapsed', false);

                    $('.side-menu-minified').css('width', '300px');

                    setTimeout(function () {
                        $('.content-menu').fadeIn(150);
                    }, 350);
                }
                else {
                    menuCollapser.data('collapsed', true);

                    $('.side-menu-minified').css('width', '60px');

                    $('.content-menu').fadeOut(10);
                }

                setTimeout(function () {
                    menuCollapser.removeClass('menu-icon-disable');
                }, 400);
            }
        });
    });
</script>

@yield('scripts')

</body>
</html>