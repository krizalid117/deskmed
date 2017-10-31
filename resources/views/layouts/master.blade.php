<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    {{--<meta http-equiv="X-UA-Compatible" content="ie=edge">--}}
    <title>Deskmed @yield('title')</title>
    <link rel="stylesheet" href="{{ URL::to('js/bootstrap-3.3.7/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('js/jquery-ui-1.12.1.custom/jquery-ui.css') }}">

    <link rel="stylesheet" href="{{ URL::to('css/variables_logged_out.css') }}">
    <link rel="stylesheet" href="{{ URL::to('css/main.css') }}?_<?php echo time(); ?>">
    <link rel="stylesheet" href="{{ URL::to('js/select2-4.0.3/dist/css/select2.min.css') }}?_<?php echo time(); ?>">
    <link rel="stylesheet" href="{{ URL::to('css/pretty.css') }}">
    @yield('stylesheets')
</head>
<body>

@yield('content')

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

@yield('scripts')

</body>
</html>