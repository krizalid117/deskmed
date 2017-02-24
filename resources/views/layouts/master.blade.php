<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    {{--<meta http-equiv="X-UA-Compatible" content="ie=edge">--}}
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ URL::to('js/bootstrap-3.3.7/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('js/jquery-ui-1.12.1.custom/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('css/main.css') }}?_<?php echo time(); ?>">

    <script src="{{ URL::to('js/jquery-3.1.1.js') }}"></script>
    <script src="{{ URL::to('js/jquery-ui-1.12.1.custom/jquery-ui.min.js') }}"></script>
    <script>
        // Change JQueryUI plugin names to fix name collision with Bootstrap.
        $.widget.bridge('uitooltip', $.ui.tooltip);
        $.widget.bridge('uibutton', $.ui.button);
    </script>
    <script src="{{ URL::to('js/bootstrap-3.3.7/js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::to('js/functions.js') }}?_<?php echo time(); ?>"></script>
    @yield('local_head')
</head>
<body>

@yield('content')

</body>
</html>