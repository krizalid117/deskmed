@extends('layouts.app')

@section('title', '| Agenda')


@section('stylesheets')
    <style>
        .agenda-master {
            display: flex;
            flex-direction: column;

            height: 100%;
        }

        .agenda-filtros {
            flex: 0 0 auto;
        }

        .agenda-container {
            flex: 1 1 auto;
            overflow: auto;

            /*min-width: 1000px;*/
        }

        .agenda-acciones {
            flex: 0 0 auto;
        }

        .agenda-daterange {
            flex: 0 0 auto;
        }

        .tbl-agenda {
            /*width: 98.7%;*/
            width: 100%;
        }

        .tbl-agenda-weekly {
            min-width: 800px;
        }

        .tbl-agenda-weekly > thead > tr > th {
            background-color: var(--normal-background-color);
            height: 25px;
        }

        .tbl-agenda-weekly > thead > tr > th:not(:first-child) {
            padding: 0 !important;
            text-align: center;
            border-left: 1px solid #ddd;
        }

        .tbl-agenda-weekly > tbody > tr > td:first-child {
            text-align: right;
        }

        .agenda-horario {
            border-bottom: none !important;
        }

        .tbl-agenda > thead > tr > th:first-child {
            border-bottom: none !important;
        }

        .agenda-horario-fila {
            text-align: right;
            vertical-align: top;
            padding: 2px 4px 0 0 !important;
        }

        .agenda-daterange {
            position: relative;
            padding: 8px 0;
        }

        .week-picker {
            position: absolute;
            z-index: 3;
        }

        .weekly-labels .weekly-labels-button {
            border: 1px solid transparent;
            padding: 3px;
            cursor: pointer;

            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;

            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .weekly-labels .weekly-labels-button:hover {
            border: 1px solid #ddd;

            -webkit-box-shadow: 0 0 10px -1px rgba(0, 0, 0, 0.55);
            -moz-box-shadow: 0 0 10px -1px rgba(0, 0, 0, 0.55);
            box-shadow: 0 0 10px -1px rgba(0, 0, 0, 0.55);
        }

        .weekly-agenda-day {
            position: relative;

            border-left: 1px solid #ddd;
            text-align: center;
            height: 65px;
            padding: 0 !important;
        }

        .hora-container {
            width: calc(100% - 4px);
            position: absolute;
            left: 2px;
            /*top: 25%;*/
            /*height: 200%;*/
            cursor: pointer;
            z-index: 1;
        }

        .hora-content {
            display: flex;
            align-items: center;
            justify-content: center;

            /*background-color: green;*/
            height: 100%;
            width: 100%;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;

            padding: 5px;
            font-size: .65em;

            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .hora-text {
            white-space: pre-wrap !important;
        }

        .agenda-acciones {
            padding-top: 15px;
            text-align: right;
        }

        #hora-masiva-mdpicker .ui-datepicker-current-day:not(.ui-state-highlight) > .ui-state-active
        , #hora-masiva-mdpicker .ui-datepicker-today:not(.ui-state-highlight) > a {
            border: 1px solid #666666 !important;
            background: #555555 url(/js/jquery-ui-1.12.1.custom/images/ui-bg_glass_20_555555_1x400.png) 50% 50% repeat-x !important;
            font-weight: normal !important;;
            color: #eeeeee !important;;
        }

        #hora-masiva-mdpicker > .ui-datepicker {
            margin: 0 auto;
        }
    </style>
@endsection


@section('content')

    <div class="agenda-master">

        <div class="agenda-filtros">
            <div class="form-group col-sm-4">
                <label for="sel-mode" class="form-label">Vista:</label>
                <select id="sel-mode" class="form-control">
                    <option value="daily" {{ ($mode === "daily" ? "selected" : "") }}>Diaria</option>
                    <option value="weekly" {{ ($mode === "weekly" ? "selected" : "") }}>Semanal</option>
                    <option value="monthly" {{ ($mode === "monthly" ? "selected" : "") }}>Mensual</option>
                </select>
            </div>
            <div class="col-sm-4 hidden-xs"></div>
            <div class="form-group col-sm-4">
                <label for="" class="form-label">Rango horario diario&nbsp;<span class="ui-icon ui-icon-help deskmed-icon-help" title="Rango horario visible durante el día."></span>:</label>
                <div id="rango-horas-container" style="display: inline-block;">
                    <input type="text" class="form-control agenda-rango-hora hidden time start" style="width: 45%; display: inline-block; text-align: center;" value="08:00"> -
                    <input type="text" class="form-control agenda-rango-hora hidden time end" style="width: 45%; display: inline-block; text-align: center;" value="22:00">
                </div>
            </div>
        </div>
        <div class="agenda-daterange">
            @if($mode === "weekly")
                <div class="weekly-labels">
                    Semana: <span class="weekly-labels-button">
                        <span id="startDate" class="bold"></span> al <span id="endDate" class="bold"></span><span class="ui-icon ui-icon-triangle-1-s"></span>
                    </span>
                </div>
                <div class="week-picker hidden"></div>
            @endif
        </div>
        <div class="agenda-container">
            @if($mode === "weekly")

                <div class="agenda-wrapper table-responsive" style="overflow: auto;">
                    <table class="table table-striped table-hover tbl-agenda tbl-agenda-weekly">
                        <thead>
                        <tr>
                            <th class="agenda-horario"></th>
                            <th data-day="1" style="width: 13.5%;">Lunes <span class="weekly-header-date"></span></th>
                            <th data-day="2" style="width: 13.5%;">Martes <span class="weekly-header-date"></span></th>
                            <th data-day="3" style="width: 13.5%;">Miércoles <span class="weekly-header-date"></span></th>
                            <th data-day="4" style="width: 13.5%;">Jueves <span class="weekly-header-date"></span></th>
                            <th data-day="5" style="width: 13.5%;">Viernes <span class="weekly-header-date"></span></th>
                            <th data-day="6" style="width: 13.5%;">Sábado <span class="weekly-header-date"></span></th>
                            <th data-day="0" style="width: 13.5%;">Domingo <span class="weekly-header-date"></span></th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr class="weekly-agenda-row" data-hora="00:00">
                                <td class="agenda-horario-fila">00:00</td>
                                <td class="weekly-agenda-day" data-dia="1"></td>
                                <td class="weekly-agenda-day" data-dia="2"></td>
                                <td class="weekly-agenda-day" data-dia="3"></td>
                                <td class="weekly-agenda-day" data-dia="4"></td>
                                <td class="weekly-agenda-day" data-dia="5"></td>
                                <td class="weekly-agenda-day" data-dia="6"></td>
                                <td class="weekly-agenda-day" data-dia="0"></td>
                            </tr>
                            <tr class="weekly-agenda-row" data-hora="01:00">
                                <td class="agenda-horario-fila">01:00</td>
                                <td class="weekly-agenda-day" data-dia="1"></td>
                                <td class="weekly-agenda-day" data-dia="2"></td>
                                <td class="weekly-agenda-day" data-dia="3"></td>
                                <td class="weekly-agenda-day" data-dia="4"></td>
                                <td class="weekly-agenda-day" data-dia="5"></td>
                                <td class="weekly-agenda-day" data-dia="6"></td>
                                <td class="weekly-agenda-day" data-dia="0"></td>
                            </tr>
                            <tr class="weekly-agenda-row" data-hora="02:00">
                                <td class="agenda-horario-fila">02:00</td>
                                <td class="weekly-agenda-day" data-dia="1"></td>
                                <td class="weekly-agenda-day" data-dia="2"></td>
                                <td class="weekly-agenda-day" data-dia="3"></td>
                                <td class="weekly-agenda-day" data-dia="4"></td>
                                <td class="weekly-agenda-day" data-dia="5"></td>
                                <td class="weekly-agenda-day" data-dia="6"></td>
                                <td class="weekly-agenda-day" data-dia="0"></td>
                            </tr>
                            <tr class="weekly-agenda-row" data-hora="03:00">
                                <td class="agenda-horario-fila">03:00</td>
                                <td class="weekly-agenda-day" data-dia="1"></td>
                                <td class="weekly-agenda-day" data-dia="2"></td>
                                <td class="weekly-agenda-day" data-dia="3"></td>
                                <td class="weekly-agenda-day" data-dia="4"></td>
                                <td class="weekly-agenda-day" data-dia="5"></td>
                                <td class="weekly-agenda-day" data-dia="6"></td>
                                <td class="weekly-agenda-day" data-dia="0"></td>
                            </tr>
                            <tr class="weekly-agenda-row" data-hora="04:00">
                                <td class="agenda-horario-fila">04:00</td>
                                <td class="weekly-agenda-day" data-dia="1"></td>
                                <td class="weekly-agenda-day" data-dia="2"></td>
                                <td class="weekly-agenda-day" data-dia="3"></td>
                                <td class="weekly-agenda-day" data-dia="4"></td>
                                <td class="weekly-agenda-day" data-dia="5"></td>
                                <td class="weekly-agenda-day" data-dia="6"></td>
                                <td class="weekly-agenda-day" data-dia="0"></td>
                            </tr>
                            <tr class="weekly-agenda-row" data-hora="05:00">
                                <td class="agenda-horario-fila">05:00</td>
                                <td class="weekly-agenda-day" data-dia="1"></td>
                                <td class="weekly-agenda-day" data-dia="2"></td>
                                <td class="weekly-agenda-day" data-dia="3"></td>
                                <td class="weekly-agenda-day" data-dia="4"></td>
                                <td class="weekly-agenda-day" data-dia="5"></td>
                                <td class="weekly-agenda-day" data-dia="6"></td>
                                <td class="weekly-agenda-day" data-dia="0"></td>
                            </tr>
                            <tr class="weekly-agenda-row" data-hora="06:00">
                                <td class="agenda-horario-fila">06:00</td>
                                <td class="weekly-agenda-day" data-dia="1"></td>
                                <td class="weekly-agenda-day" data-dia="2"></td>
                                <td class="weekly-agenda-day" data-dia="3"></td>
                                <td class="weekly-agenda-day" data-dia="4"></td>
                                <td class="weekly-agenda-day" data-dia="5"></td>
                                <td class="weekly-agenda-day" data-dia="6"></td>
                                <td class="weekly-agenda-day" data-dia="0"></td>
                            </tr>
                            <tr class="weekly-agenda-row" data-hora="07:00">
                                <td class="agenda-horario-fila">07:00</td>
                                <td class="weekly-agenda-day" data-dia="1"></td>
                                <td class="weekly-agenda-day" data-dia="2"></td>
                                <td class="weekly-agenda-day" data-dia="3"></td>
                                <td class="weekly-agenda-day" data-dia="4"></td>
                                <td class="weekly-agenda-day" data-dia="5"></td>
                                <td class="weekly-agenda-day" data-dia="6"></td>
                                <td class="weekly-agenda-day" data-dia="0"></td>
                            </tr>
                            <tr class="weekly-agenda-row" data-hora="08:00">
                                <td class="agenda-horario-fila">08:00</td>
                                <td class="weekly-agenda-day" data-dia="1"></td>
                                <td class="weekly-agenda-day" data-dia="2"></td>
                                <td class="weekly-agenda-day" data-dia="3"></td>
                                <td class="weekly-agenda-day" data-dia="4"></td>
                                <td class="weekly-agenda-day" data-dia="5"></td>
                                <td class="weekly-agenda-day" data-dia="6"></td>
                                <td class="weekly-agenda-day" data-dia="0"></td>
                            </tr>
                            <tr class="weekly-agenda-row" data-hora="09:00">
                                <td class="agenda-horario-fila">09:00</td>
                                <td class="weekly-agenda-day" data-dia="1"></td>
                                <td class="weekly-agenda-day" data-dia="2"></td>
                                <td class="weekly-agenda-day" data-dia="3"></td>
                                <td class="weekly-agenda-day" data-dia="4"></td>
                                <td class="weekly-agenda-day" data-dia="5"></td>
                                <td class="weekly-agenda-day" data-dia="6"></td>
                                <td class="weekly-agenda-day" data-dia="0"></td>
                            </tr>
                            <tr class="weekly-agenda-row" data-hora="10:00">
                                <td class="agenda-horario-fila">10:00</td>
                                <td class="weekly-agenda-day" data-dia="1"></td>
                                <td class="weekly-agenda-day" data-dia="2"></td>
                                <td class="weekly-agenda-day" data-dia="3"></td>
                                <td class="weekly-agenda-day" data-dia="4"></td>
                                <td class="weekly-agenda-day" data-dia="5"></td>
                                <td class="weekly-agenda-day" data-dia="6"></td>
                                <td class="weekly-agenda-day" data-dia="0"></td>
                            </tr>
                            <tr class="weekly-agenda-row" data-hora="11:00">
                                <td class="agenda-horario-fila">11:00</td>
                                <td class="weekly-agenda-day" data-dia="1"></td>
                                <td class="weekly-agenda-day" data-dia="2"></td>
                                <td class="weekly-agenda-day" data-dia="3"></td>
                                <td class="weekly-agenda-day" data-dia="4"></td>
                                <td class="weekly-agenda-day" data-dia="5"></td>
                                <td class="weekly-agenda-day" data-dia="6"></td>
                                <td class="weekly-agenda-day" data-dia="0"></td>
                            </tr>
                            <tr class="weekly-agenda-row" data-hora="12:00">
                                <td class="agenda-horario-fila">12:00</td>
                                <td class="weekly-agenda-day" data-dia="1"></td>
                                <td class="weekly-agenda-day" data-dia="2"></td>
                                <td class="weekly-agenda-day" data-dia="3"></td>
                                <td class="weekly-agenda-day" data-dia="4"></td>
                                <td class="weekly-agenda-day" data-dia="5"></td>
                                <td class="weekly-agenda-day" data-dia="6"></td>
                                <td class="weekly-agenda-day" data-dia="0"></td>
                            </tr>
                            <tr class="weekly-agenda-row" data-hora="13:00">
                                <td class="agenda-horario-fila">13:00</td>
                                <td class="weekly-agenda-day" data-dia="1"></td>
                                <td class="weekly-agenda-day" data-dia="2"></td>
                                <td class="weekly-agenda-day" data-dia="3"></td>
                                <td class="weekly-agenda-day" data-dia="4"></td>
                                <td class="weekly-agenda-day" data-dia="5"></td>
                                <td class="weekly-agenda-day" data-dia="6"></td>
                                <td class="weekly-agenda-day" data-dia="0"></td>
                            </tr>
                            <tr class="weekly-agenda-row" data-hora="14:00">
                                <td class="agenda-horario-fila">14:00</td>
                                <td class="weekly-agenda-day" data-dia="1"></td>
                                <td class="weekly-agenda-day" data-dia="2"></td>
                                <td class="weekly-agenda-day" data-dia="3"></td>
                                <td class="weekly-agenda-day" data-dia="4"></td>
                                <td class="weekly-agenda-day" data-dia="5"></td>
                                <td class="weekly-agenda-day" data-dia="6"></td>
                                <td class="weekly-agenda-day" data-dia="0"></td>
                            </tr>
                            <tr class="weekly-agenda-row" data-hora="15:00">
                                <td class="agenda-horario-fila">15:00</td>
                                <td class="weekly-agenda-day" data-dia="1"></td>
                                <td class="weekly-agenda-day" data-dia="2"></td>
                                <td class="weekly-agenda-day" data-dia="3"></td>
                                <td class="weekly-agenda-day" data-dia="4"></td>
                                <td class="weekly-agenda-day" data-dia="5"></td>
                                <td class="weekly-agenda-day" data-dia="6"></td>
                                <td class="weekly-agenda-day" data-dia="0"></td>
                            </tr>
                            <tr class="weekly-agenda-row" data-hora="16:00">
                                <td class="agenda-horario-fila">16:00</td>
                                <td class="weekly-agenda-day" data-dia="1"></td>
                                <td class="weekly-agenda-day" data-dia="2"></td>
                                <td class="weekly-agenda-day" data-dia="3"></td>
                                <td class="weekly-agenda-day" data-dia="4"></td>
                                <td class="weekly-agenda-day" data-dia="5"></td>
                                <td class="weekly-agenda-day" data-dia="6"></td>
                                <td class="weekly-agenda-day" data-dia="0"></td>
                            </tr>
                            <tr class="weekly-agenda-row" data-hora="17:00">
                                <td class="agenda-horario-fila">17:00</td>
                                <td class="weekly-agenda-day" data-dia="1"></td>
                                <td class="weekly-agenda-day" data-dia="2"></td>
                                <td class="weekly-agenda-day" data-dia="3"></td>
                                <td class="weekly-agenda-day" data-dia="4"></td>
                                <td class="weekly-agenda-day" data-dia="5"></td>
                                <td class="weekly-agenda-day" data-dia="6"></td>
                                <td class="weekly-agenda-day" data-dia="0"></td>
                            </tr>
                            <tr class="weekly-agenda-row" data-hora="18:00">
                                <td class="agenda-horario-fila">18:00</td>
                                <td class="weekly-agenda-day" data-dia="1"></td>
                                <td class="weekly-agenda-day" data-dia="2"></td>
                                <td class="weekly-agenda-day" data-dia="3"></td>
                                <td class="weekly-agenda-day" data-dia="4"></td>
                                <td class="weekly-agenda-day" data-dia="5"></td>
                                <td class="weekly-agenda-day" data-dia="6"></td>
                                <td class="weekly-agenda-day" data-dia="0"></td>
                            </tr>
                            <tr class="weekly-agenda-row" data-hora="19:00">
                                <td class="agenda-horario-fila">19:00</td>
                                <td class="weekly-agenda-day" data-dia="1"></td>
                                <td class="weekly-agenda-day" data-dia="2"></td>
                                <td class="weekly-agenda-day" data-dia="3"></td>
                                <td class="weekly-agenda-day" data-dia="4"></td>
                                <td class="weekly-agenda-day" data-dia="5"></td>
                                <td class="weekly-agenda-day" data-dia="6"></td>
                                <td class="weekly-agenda-day" data-dia="0"></td>
                            </tr>
                            <tr class="weekly-agenda-row" data-hora="20:00">
                                <td class="agenda-horario-fila">20:00</td>
                                <td class="weekly-agenda-day" data-dia="1"></td>
                                <td class="weekly-agenda-day" data-dia="2"></td>
                                <td class="weekly-agenda-day" data-dia="3"></td>
                                <td class="weekly-agenda-day" data-dia="4"></td>
                                <td class="weekly-agenda-day" data-dia="5"></td>
                                <td class="weekly-agenda-day" data-dia="6"></td>
                                <td class="weekly-agenda-day" data-dia="0"></td>
                            </tr>
                            <tr class="weekly-agenda-row" data-hora="21:00">
                                <td class="agenda-horario-fila">21:00</td>
                                <td class="weekly-agenda-day" data-dia="1"></td>
                                <td class="weekly-agenda-day" data-dia="2"></td>
                                <td class="weekly-agenda-day" data-dia="3"></td>
                                <td class="weekly-agenda-day" data-dia="4"></td>
                                <td class="weekly-agenda-day" data-dia="5"></td>
                                <td class="weekly-agenda-day" data-dia="6"></td>
                                <td class="weekly-agenda-day" data-dia="0"></td>
                            </tr>
                            <tr class="weekly-agenda-row" data-hora="22:00">
                                <td class="agenda-horario-fila">22:00</td>
                                <td class="weekly-agenda-day" data-dia="1"></td>
                                <td class="weekly-agenda-day" data-dia="2"></td>
                                <td class="weekly-agenda-day" data-dia="3"></td>
                                <td class="weekly-agenda-day" data-dia="4"></td>
                                <td class="weekly-agenda-day" data-dia="5"></td>
                                <td class="weekly-agenda-day" data-dia="6"></td>
                                <td class="weekly-agenda-day" data-dia="0"></td>
                            </tr>
                            <tr class="weekly-agenda-row" data-hora="23:00">
                                <td class="agenda-horario-fila">23:00</td>
                                <td class="weekly-agenda-day" data-dia="1"></td>
                                <td class="weekly-agenda-day" data-dia="2"></td>
                                <td class="weekly-agenda-day" data-dia="3"></td>
                                <td class="weekly-agenda-day" data-dia="4"></td>
                                <td class="weekly-agenda-day" data-dia="5"></td>
                                <td class="weekly-agenda-day" data-dia="6"></td>
                                <td class="weekly-agenda-day" data-dia="0"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            @else
                Modo inexistente.
            @endif
        </div>
        <div class="agenda-acciones">
            <div class="btn-group dropup">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Crear&nbsp;{{--</button>--}}
                {{--<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                    <span class="caret"></span>
                    {{--<span class="sr-only">Toggle Dropdown</span>--}}
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li><a id="new-hora-single" href="#">Hora individual</a></li>
                    <li><a id="new-hora-group" href="#">Creación masiva de horas para el mes</a></li>
                    {{--<li role="separator" class="divider"></li>--}}
                </ul>
            </div>
        </div>

    </div>
@endsection


@section('scripts')
    <script type="text/javascript" src="{{ URL::to('js/multidatespicker/jquery-ui.multidatespicker.js') }}"></script>
    <script type="text/javascript">

        var tblAgenda = $('.tbl-agenda');
        var agendaContainer = $('.agenda-container');

        $(function () {

            @if($mode === "weekly")

                var weekpicker = $('.week-picker');

                var selectCurrentWeek = function (monthChange) {
                    setTimeout(function () {
                        weekpicker.find('.ui-datepicker-current-day a').addClass('ui-state-active');

                        setTimeout(function () {
                            if (!weekpicker.hasClass('hidden') && !monthChange) {
                                weekpicker.addClass('hidden');

                                var icon = $('.weekly-labels-button').find('.ui-icon');

                                icon.toggleClass('ui-icon-triangle-1-s');
                                icon.toggleClass('ui-icon-triangle-1-n');
                            }
                        }, 200);
                    }, 1);
                };

                var selectWeek = function () {

                    updateWeekDates(function () {
                        loadAgenda();
                    });
                };

                weekpicker.datepicker({
                    showOtherMonths: true,
                    selectOtherMonths: true,
                    onSelect: selectWeek,
                    beforeShowDay: function (date) {
                        var cssClass = '';

                        var today = new Date();

                        var start = ($('#startDate').data('date') ? $.datepicker.parseDate($.datepicker._defaults.dateFormat, $('#startDate').data('date')) : null) || new Date(today.getFullYear(), today.getMonth(), today.getDate() - today.getDay() + 1);
                        var end = ($('#endDate').data('date') ? $.datepicker.parseDate($.datepicker._defaults.dateFormat, $('#endDate').data('date')) : null) || new Date(today.getFullYear(), today.getMonth(), today.getDate() - today.getDay() + 7);

                        if (date >= start && date <= end) {
                            cssClass = 'ui-datepicker-current-day';
                        }

                        return [true, cssClass];
                    },
                    onChangeMonthYear: function (year, month, inst) {
                        selectCurrentWeek(true);
                    }
                });

                $(document).on('mousemove', '.week-picker .ui-datepicker-calendar tr', function () {
                    $(this).find('td a').addClass('ui-state-hover');
                });
                $(document).on('mouseleave', '.week-picker .ui-datepicker-calendar tr', function () {
                    $(this).find('td a').removeClass('ui-state-hover');
                });

                weekpicker.datepicker("setDate", "{{ date('d-m-Y') }}");

                $('.weekly-labels-button').click(function () {
                    weekpicker.toggleClass('hidden');
                    $(this).find('.ui-icon').toggleClass('ui-icon-triangle-1-s');
                    $(this).find('.ui-icon').toggleClass('ui-icon-triangle-1-n');
                });

                updateWeekDates(function () {
                    loadAgenda();
                });

                function updateWeekDates(callback) {
                    var date = weekpicker.datepicker('getDate');

                    var day = date.getDay() === 0 ? 7 : date.getDay();

                    var startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - (day - 1));
                    var endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - (day - 7));
                    var dateFormat = $.datepicker._defaults.dateFormat;

                    //Se recorren los días de la semana para dejar las fechas en la cabecera de la tabla en formato dd/mm
                    for (var i = 0; i < 7; i++) {
                        var indice = (i + 1) === 7 ? 0 : (i + 1);
                        var newDate = new Date(startDate.getFullYear(), startDate.getMonth(), startDate.getDate() + i);
                        var fecha = $.datepicker.formatDate(dateFormat, newDate);
                        var fechaHead = $.datepicker.formatDate("dd/mm", newDate);

                        $('.tbl-agenda-weekly')
                            .children('thead').children('tr')
                            .children('th[data-day="' + indice + '"]')
                            .data('date', fecha)
                                .find('.weekly-header-date')
                                .text(fechaHead);

                        $('.weekly-agenda-day[data-dia="' + indice + '"]').data('date', fecha);
                    }

                    var s = $.datepicker.formatDate(dateFormat, startDate);
                    var e = $.datepicker.formatDate(dateFormat, endDate);

                    $('#startDate').text(s).data('date', s);
                    $('#endDate').text(e).data('date', e);

                    selectCurrentWeek(false);

                    if (callback) {
                        callback();
                    }
                }

            @endif

            tblAgenda.floatThead({
                scrollContainer: function ($table) {
                    return $table.closest('.agenda-wrapper');
                }
            });

            $('.agenda-rango-hora').timepicker({
                'timeFormat': 'H:i',
                'step': 60,
                'minTime': '00:00',
                'maxTime': '23:45',
                'forceRoundTime': true,
                useSelect: true
            }).on('changeTime', function () {
                updateEndTime($('.agenda-rango-hora.start'), $('.agenda-rango-hora.end'), function () {
                    ocultarFilasHorario();

                    tblAgenda.floatThead('reflow');
                });
            });

            $(window).resize(function () {
                var extra = (document.documentElement.clientWidth <= 767) ? 15 : 0;

                $('.agenda-wrapper').css('max-height', (agendaContainer.outerHeight() - extra) + 'px');

                $('div[id^="hora-"]').each(function () {

                    var $this = $(this);
                    if (!$this.hasClass('hora-resizing')) {

                        $this.addClass('hora-resizing');

                        setTimeout(function () {
                            $this.find('.hora-text').html($this.data('text')).promise().done(function () {
                                ellipsizeTextBox($this.attr('id'));

                                $this.removeClass('hora-resizing');
                            });
                        }, 1);
                    }
                });
            }).resize();

            $('#new-hora-single').click(function (e) {
                e.preventDefault();

                editarAgregarHoraSimple('add');
            });

            $('#new-hora-group').click(function (e) {
                e.preventDefault();

                creacionHorasMasivo();
            });

            tblAgenda.on('click', 'div[id^="hora-"]', function () {
                var data = $(this).data('datos');

                editarAgregarHoraSimple('edit', {
                    codigo: data.id,
                    nombre: data.nombre,
                    fecha: invertirFecha(data.fecha),
                    hora_inicio: data.hora_inicio,
                    hora_termino: data.hora_termino,
                    color: data.hex_color
                });
            });

            updateEndTime($('.agenda-rango-hora.start'), $('.agenda-rango-hora.end'), function () {
                ocultarFilasHorario();
            });
        });

        function loadAgenda() {
            var tbody = $('.tbl-agenda').children('tbody');

            @if ($mode === "daily")

            @elseif ($mode === "weekly")

            loadWeeklyAgenda();

            @elseif ($mode === "monthly")

            @endif
        }

        function ocultarFilasHorario() {
            var horaStart = parseInt($('.agenda-rango-hora.start').val().split(':')[0]);
            var horaEnd = parseInt($('.agenda-rango-hora.end').val().split(':')[0]);

            $('.weekly-agenda-row').each(function () {
                var $this = $(this);
                var hora = parseInt($this.data('hora').split(':')[0]);

                if (hora >= horaStart && hora <= horaEnd) {
                    if ($this.hasClass('hidden')) {
                        $this.removeClass('hidden');
                    }
                }
                else {
                    if (!$this.hasClass('hidden')) {
                        $this.addClass('hidden');
                    }
                }
            });
        }

        function updateEndTime($start, $end, callback) {

            if ($start.hasClass('start') && $end.hasClass('end')) {

                var endTimeNewMin = new Date($start.timepicker('getTime').getTime() + ($start.timepicker('option', 'step') * 60000));

                if ($end.timepicker('getTime') < $start.timepicker('getTime')) {
                    $end.timepicker('setTime', endTimeNewMin);
                }

                $end.timepicker('option', { 'minTime': endTimeNewMin });
            }

            if (callback) {
                callback();
            }
        }

        function loadWeeklyAgenda() {
            sendPost('{{ route('user.getagenda') }}', {
                _token: '{{ csrf_token() }}',
                inicio: $('#startDate').data('date'),
                termino: $('#endDate').data('date')
            }, function (res) {
                $('.weekly-agenda-day').empty();

                for (var i = 0; i < res.horas.length; i++) {
                    var hora = res.horas[i];

                    var horaInicio = hora.hora_inicio.split(':')[0];
                    var minutosInicio = hora.hora_inicio.split(':')[1];
                    var duracion = Math.floor(((new Date('2011/01/01 ' + hora.hora_termino) - new Date('2011/01/01 ' + hora.hora_inicio)) / 1000) / 60);

                    var minutosInicioP = getMinutesPercent(minutosInicio);
                    var duracionP = getMinutesPercent(duracion);

                    var horaSquare = '<div id="hora-' + hora.id + '" class="hora-container" style="top: ' + minutosInicioP + '%; height: ' + duracionP + '%;" data-text="' + htmlEntities('<span class="bold">(' + hora.hora_inicio + '-' + hora.hora_termino + '): </span>' + hora.nombre)+ '">' +
                        '<div class="hora-content" style="background-color: ' + hora.hex_color + ';" title="(' + hora.hora_inicio + ' - ' + hora.hora_termino + '):' + hora.nombre + '">' +
                            '<span class="hora-text"><span class="bold">(' + hora.hora_inicio + '-' + hora.hora_termino + '): </span>' + hora.nombre + '</span>' +
                        '</div>' +
                    '</div>';

                    $('.weekly-agenda-row[data-hora="' + horaInicio + ':00"]').children('.weekly-agenda-day').each(function () {
                        if ($(this).data('date') === invertirFecha(hora.fecha)) {
                            $(this).append(horaSquare);

                            $('#hora-' + hora.id).data('datos', hora);

                            ellipsizeTextBox('hora-' + hora.id);
                        }
                    });
                }
            });
        }

        function editarAgregarHoraSimple(action, hora) {
            var h = {
                codigo: 0,
                nombre: "",
                fecha: "{{ date('d-m-Y') }}",
                hora_inicio: "",
                hora_termino: "",
                color: "f1f1f1"
            };

            if (action === 'edit') {
                h = hora;
            }

            $('<div id="dlg-new-hora-single" style="padding-top: 15px;">' +
                '<div class="col-sm-6 form-group">' +
                    '<label for="hora-single-nombre" class="form-label">Nombre</label>' +
                    '<input type="text" id="hora-single-nombre" class="form-control" value="' + h.nombre + '">' +
                '</div>' +
                '<div class="col-sm-6 form-group">' +
                    '<label for="hora-single-fecha" class="form-label">Fecha</label>' +
                    '<input type="text" id="hora-single-fecha" class="form-control" value="' + h.fecha + '" placeholder="dd-mm-yyyy" readonly>' +
                '</div>' +
                '<div class="col-sm-6 form-group">' +
                    '<label for="hora-single-hora-start" class="form-label">Hora</label>' +
                    '<div class="form-control" style="border: none; box-shadow: none;">' +
                        '<input type="text" id="hora-single-hora-start" class="hora-single-time time start" placeholder="HH" value="' + h.hora_inicio + '"> - ' +
                        '<input type="text" id="hora-single-hora-end" class="hora-single-time time end" placeholder="MM" value="' + h.hora_termino + '">' +
                    '</div>' +
                '</div>' +
                '<div class="col-sm-6 form-group">' +
                    '<label for="hora-single-color" class="form-label">Color</label>' +
                    '<input id="hora-single-color" class="form-control jscolor" value="' + h.color + '" placeholder="#F1F1F1" readonly>' +
                '</div>' +
            '</div>').dialog({
                title: (action === 'add') ? "Nueva hora" : "Editar hora: \"" + h.nombre + "\" (" + h.hora_inicio + "-" + h.hora_termino + ")",
                width: 440,
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
                        text: "Cancelar",
                        'class': 'btn',
                        click: function () {
                            $(this).dialog('close');
                        }
                    },
                    {
                        text: "Guardar",
                        'class': 'btn btn-primary',
                        click: function () {
                            sendPost('{{ route('user.saveagenda_single') }}', {
                                _token: '{{ csrf_token() }}',
                                action: action,
                                id: h.codigo,
                                nombre: $('#hora-single-nombre').val(),
                                fecha: $('#hora-single-fecha').val(),
                                hora_inicio: $('#hora-single-hora-start').val(),
                                hora_termino: $('#hora-single-hora-end').val(),
                                color: $('#hora-single-color').val()
                            }, function (res) {
                                mensajes.alerta((action === 'add') ? "¡Tu hora ha sido creada!" : "Tu hora ha sido modificada correctamente.", "Horas médicas", function () {
                                    $("#dlg-new-hora-single").dialog('close');
                                    loadAgenda();
                                });
                            });
                        }
                    }
                ]
            });

            var color = new jscolor($('#hora-single-color')[0], {
                hash: true
            });

            $('#hora-single-fecha').datepicker();

            $('.hora-single-time').timepicker({
                'timeFormat': 'H:i',
                'step': 15,
                'minTime': '00:00',
                'maxTime': '23:45',
                'forceRoundTime': true,
                useSelect: true
            }).on('changeTime', function () {
                updateEndTime($('.hora-single-time.start'), $('.hora-single-time.end'));
            });
        }

        function creacionHorasMasivo() {
            var nmonth = parseInt('{{ date('m') }}') - 1;
            var anio = '{{ date('Y') }}';

            var horas = [];

            $('<div id="dlg-new-hora-group">' +
                '<fieldset class="fs-collapsable" data-collapsed="false">' +
                    '<legend class="fs-collapsable-title"><span class="ui-icon ui-icon-minus"></span>Días en que se crearán las horas</legend>' +
                    '<div class="fs-collapsable-content">' +
                        'Seleccione los días en los que se crearán las horas:' +
                        '<div id="hora-masiva-mdpicker" style="margin-top: 10px;"></div>' +
                    '</div>' +
                '</fieldset>' +
                '<fieldset class="fs-collapsable" data-collapsed="false">' +
                    '<legend class="fs-collapsable-title"><span class="ui-icon ui-icon-minus"></span>Horas a crear por día</legend>' +
                    '<div class="fs-collapsable-content">' +
                        'Especifique las horas a crear para <span class="bold">cada día</span> seleccionado:' +
                        '<div class="table-responsive" style="margin-top: 10px; overflow: auto; max-height: 100px;">' +
                            '<table id="tbl-horas-masivas" class="table table-condensed table-bordered" style="margin-bottom: 0;">' +
                                '<thead>' +
                                    '<tr style="background-color: #fff;">' +
                                        '<th style="text-align: center;">Nombre</th>' +
                                        '<th style="text-align: center; width: 100px;">Hora</th>' +
                                        '<th style="text-align: center; width: 50px;">' +
                                            '<span id="delete-all-horas" class="ui-icon ui-icon-trash" style="cursor: pointer;" title="Eliminar todas las horas"></span>' +
                                        '</th>' +
                                    '</tr>' +
                                '</thead>' +
                                '<tbody id="tbody-horas-masivas">' +
                                    '<tr>' +
                                        '<td colspan="3" style="text-align: center;">' +
                                            'No hay horas a ser creadas' +
                                        '</td>' +
                                    '</tr>' +
                                '</tbody>' +
                            '</table>' +
                        '</div>' +
                        '<div style="margin-top: 10px;">' +
                            '<span style="float: left;" id="nhoras-masiva">Horas a crear: <span class="bold">0</span></span>' +
                            '<button id="btn-add-hora-masiva-group" class="btn btn-xs btn-info" style="font-size: .9em; margin-left: 10px; float: right; margin-bottom: 10px; width: 145px;">Agregar grupo de horas</button>' +
                            '<button id="btn-add-hora-masiva" class="btn btn-xs btn-primary" style="font-size: .9em; float: right; margin-bottom: 10px; width: 145px;">Agregar hora simple</button>' +
                        '</div>' +
                    '</div>' +
                '</fieldset>' +
            '</div>').dialog({
                title: 'Creación de horas para mes de ' + $.datepicker.regional["es"].monthNames[nmonth] + ' de ' + anio,
                width: 800,
                classes: { 'ui-dialog': 'dialog-responsive' },
                position: { my: 'center top', at: 'center top+65' },
                resizable: false,
                modal: true,
                autoOpen: true,
                close: function () {
                    $(this).dialog('destroy').remove();
                },
                closeOnEscape: false,
                buttons: [
                    {
                        text: "Cancelar",
                        'class': 'btn',
                        click: function () {
                            $(this).dialog('close');
                        }
                    },
                    {
                        text: "Guardar",
                        'class': 'btn btn-primary',
                        click: function () {
                            sendPost('{{ route('user.saveagenda_masive') }}', {
                                _token: '{{ csrf_token() }}'
                            }, function (res) {
                                mensajes.alerta("¡Tus horas fueron creadas!", "Horas médicas", function () {
                                    $("#dlg-new-hora-group").dialog('close');
                                    loadAgenda();
                                });
                            });
                        }
                    }
                ]
            });

            $('#delete-all-horas').click(function () {
                mensajes.confirmacion_sino('¿Está seguro de quitar <span class="bold">TODAS</span> las horas de la lista de horas a crear?', function () {
                    horas = [];
                    actualizarHorasMasivas();
                });
            });

            $('#hora-masiva-mdpicker').multiDatesPicker({
                hideIfNoPrevNext: true,
                changeMonth: false,
                changeYear: false,
                minDate: '01-{{ date('m-Y') }}',
                maxDate: '{{ date('t-m-Y') }}'
            });

            $('#tbl-horas-masivas').floatThead({
                scrollContainer: function ($table) {
                    return $table.closest('.table-responsive');
                }
            });

            $('#btn-add-hora-masiva').click(function () {

                var horasIniciales = [ "00:00", "00:15" ];

                if (horas.length > 0) {
                    horasIniciales[0] = horas[horas.length - 1].hora_termino;

                    var horaTerminoInicial = new Date(new Date('2011/01/01 ' + horasIniciales[0] + ':00').getTime() + (15 * 60000));

                    var h = horaTerminoInicial.getHours();
                    var m = horaTerminoInicial.getMinutes();

                    h = h < 10 ? ('0' + h) : ('' + h);
                    m = m < 10 ? ('0' + m) : ('' + m);

                    horasIniciales[1] = h + ':' + m;
                }

                $('<div id="dlg-new-hora-single" style="padding-top: 15px;">' +
                    '<div class="col-sm-12 form-group">' +
                        '<label for="hora-single-nombre" class="form-label">Nombre</label>' +
                        '<input type="text" id="hora-single-nombre" class="form-control">' +
                    '</div>' +
                    '<div class="col-sm-6 form-group">' +
                        '<label for="hora-single-hora-start" class="form-label">Hora</label>' +
                        '<div class="form-control" style="border: none; box-shadow: none;">' +
                            '<input type="text" id="hora-single-hora-start" class="hora-single-time time start" placeholder="HH" value="' + horasIniciales[0] + '"> - ' +
                            '<input type="text" id="hora-single-hora-end" class="hora-single-time time end" placeholder="MM" value="' + horasIniciales[1] + '">' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-sm-6 form-group">' +
                        '<label for="hora-single-color" class="form-label">Color</label>' +
                        '<input id="hora-single-color" class="form-control jscolor" value="#F1F1F1" placeholder="#F1F1F1" readonly>' +
                    '</div>' +
                '</div>').dialog({
                    title: "Nueva hora",
                    width: 440,
                    classes: { 'ui-dialog': 'dialog-responsive' },
                    position: { my: 'center center', at: 'center center', of: '#dlg-new-hora-group' },
                    resizable: false,
                    modal: true,
                    autoOpen: true,
                    close: function () {
                        $(this).dialog('destroy').remove();
                    },
                    closeOnEscape: false,
                    buttons: [
                        {
                            text: "Cancelar",
                            'class': 'btn',
                            click: function () {
                                $(this).dialog('close');
                            }
                        },
                        {
                            text: "Aceptar",
                            'class': 'btn btn-primary',
                            click: function () {

                                var ok = true;

                                if ($.trim($('#hora-single-nombre').val()) === "") {
                                    ok = "Debe ingresar un nombre";
                                }
                                else if ($.trim($('#hora-single-color').val()) === "") {
                                    ok = "Debe ingresar un color";
                                }
                                else if ($.trim($('#hora-single-hora-start').val()) === "") {
                                    ok = "Debe ingresar una hora de inicio";
                                }
                                else if ($.trim($('#hora-single-hora-end').val()) === "") {
                                    ok = "Debe ingresar una hora de término";
                                }

                                if (ok === true) {
                                    var overlapErrorObject = {};

                                    var checkNoOverlapOccurs = function () {
                                        if (horas.length > 0) {
                                            var hi1 = +new Date('2011/01/01 ' + $.trim($('#hora-single-hora-start').val()) + ':00');
                                            var ht1 = +new Date('2011/01/01 ' + $.trim($('#hora-single-hora-end').val()) + ':00');

                                            for (var i = 0; i < horas.length; i++) {
                                                var hi2 = +new Date('2011/01/01 ' + horas[i].hora_inicio + ':00');
                                                var ht2 = +new Date('2011/01/01 ' + horas[i].hora_termino + ':00');

                                                if ((hi1 < ht2) && (hi2 < ht1)) {
                                                    overlapErrorObject = horas[i];

                                                    return false;
                                                }
                                            }

                                            return true;
                                        }
                                        else {
                                            return true;
                                        }
                                    };

                                    if (checkNoOverlapOccurs()) {

                                        horas.push({
                                            nombre: $.trim($('#hora-single-nombre').val()),
                                            color: $.trim($('#hora-single-color').val()),
                                            hora_inicio: $('#hora-single-hora-start').val(),
                                            hora_termino: $('#hora-single-hora-end').val()
                                        });

                                        actualizarHorasMasivas();

                                        $('#dlg-new-hora-single').dialog('close');
                                    }
                                    else {
                                        mensajes.alerta('La hora "<span style="color: ' + overlapErrorObject.color + ';">' + overlapErrorObject.nombre + '</span>" <span class="bold">(' + overlapErrorObject.hora_inicio + ' - ' + overlapErrorObject.hora_termino + ')</span> se superpone con la hora que intentas crear. Por favor, haga cambios en el rango horario antes de continuar.');
                                    }
                                }
                                else {
                                    mensajes.alerta(ok + '.');
                                }
                            }
                        }
                    ]
                });

                var color = new jscolor($('#hora-single-color')[0], {
                    hash: true
                });

                $('.hora-single-time').timepicker({
                    'timeFormat': 'H:i',
                    'step': 15,
                    'minTime': '00:00',
                    'maxTime': '23:45',
                    'forceRoundTime': true,
                    useSelect: true
                }).on('changeTime', function () {
                    updateEndTime($('.hora-single-time.start'), $('.hora-single-time.end'));
                });
            });

            $('#btn-add-hora-masiva-group').click(function () {
                $('<div id="dlg-grupo-horas-masiva">' +
                    '<div class="col-sm-12 form-group">' +
                        '<label for="grupo-horas-nhoras" class="form-label">Cantidad de horas</label>' +
                        '<select id="grupo-horas-nhoras" class="form-control">' +
                            '<option value="1" selected>1</option>' +
                            '<option value="2">2</option>' +
                            '<option value="3">3</option>' +
                            '<option value="4">4</option>' +
                            '<option value="5">5</option>' +
                            '<option value="6">6</option>' +
                            '<option value="7">7</option>' +
                            '<option value="8">8</option>' +
                            '<option value="9">9</option>' +
                            '<option value="10">10</option>' +
                            '<option value="11">11</option>' +
                            '<option value="12">12</option>' +
                            '<option value="13">13</option>' +
                            '<option value="14">14</option>' +
                            '<option value="15">15</option>' +
                            '<option value="16">16</option>' +
                            '<option value="17">17</option>' +
                            '<option value="18">18</option>' +
                            '<option value="19">19</option>' +
                            '<option value="20">20</option>' +
                        '</select>' +
                    '</div>' +
                    '<div class="col-sm-12 form-group">' +
                        '<label for="grupo-horas-min" class="form-label">Minutos por hora</label>' +
                        '<select id="grupo-horas-min" class="form-control">' +
                            '<option value="15">15 minutos</option>' +
                            '<option value="30">30 minutos</option>' +
                            '<option value="45">45 minutos</option>' +
                            '<option value="60">60 minutos</option>' +
                            '<option value="75">75 minutos</option>' +
                            '<option value="90">90 minutos</option>' +
                            '<option value="105">105 minutos</option>' +
                            '<option value="120">120 minutos</option>' +
                        '</select>' +
                    '</div>' +
                    '<div class="col-sm-12 form-group">' +
                        '<label for="grupo-horas-nombre" class="form-label">Nombre por hora</label>' +
                        '<input type="text" id="grupo-horas-nombre" class="form-control">' +
                    '</div>' +
                    '<div class="col-sm-12 form-group">' +
                        '<label for="grupo-horas-color" class="form-label">Color por hora</label>' +
                        '<div class="input-group">' +
                            '<input type="text" id="grupo-horas-color" class="form-control jscolor" value="#F1F1F1" readonly>' +
                            '<span class="input-group-addon">' +
                                '<div style="display: flex; align-items: center; justify-content: center;">' +
                                    '<input style="cursor: pointer;" type="checkbox" aria-label="Color aleatorio" id="grupo-horas-color-random">' +
                                    '<label style="margin-left: 5px; margin-bottom: 0; cursor: pointer; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none;" for="grupo-horas-color-random">Aleatorio</label>' +
                                '</div>' +
                            '</span>' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-sm-12 form-group">' +
                        '<hr>' +
                        '<span id="grupo-horas-info">Se agregará una hora de 15 minutos al listado de horas.</span>' +
                    '</div>' +
                '</div>').dialog({
                    title: "Nuevo grupo de horas",
                    width: 440,
                    classes: { 'ui-dialog': 'dialog-responsive' },
                    position: { my: 'center center', at: 'center center', of: '#dlg-new-hora-group' },
                    resizable: false,
                    modal: true,
                    autoOpen: true,
                    close: function () {
                        $(this).dialog('destroy').remove();
                    },
                    closeOnEscape: false,
                    buttons: [
                        {
                            text: "Cancelar",
                            'class': 'btn',
                            click: function () {
                                $(this).dialog('close');
                            }
                        },
                        {
                            text: "Aceptar",
                            'class': 'btn btn-primary',
                            click: function () {
                                if ($.trim($('#grupo-horas-nombre').val()) !== "") {
                                    if ($.trim($('#grupo-horas-color').val()) !== "" || $('#grupo-horas-color-random').is(':checked')) {


                                        $('#dlg-grupo-horas-masiva').dialog('close');
                                    }
                                    else {
                                        mensajes.alerta("Debe ingresar un color para las horas a crear.");
                                    }
                                }
                                else {
                                    mensajes.alerta("Debe ingresar un nombre para las horas a crear.");
                                }
                            }
                        }
                    ]
                });

                var colorElement = $('#grupo-horas-color').clone(true);

                var color = new jscolor($('#grupo-horas-color')[0], {
                    hash: true
                });

                $('#grupo-horas-color-random').change(function () {
                    var checked = $(this).is(':checked');

                    if (checked) {
                        colorElement.replaceAll('#grupo-horas-color');

                        colorElement = colorElement.clone();
                    }
                    else {
                        color = new jscolor($('#grupo-horas-color')[0], {
                            hash: true
                        });
                    }

                    $('#grupo-horas-color')
                        .prop('disabled', checked)
                        .val(checked ? '' : '#F1F1F1')
                        .css('background-color', '#F1F1F1');
                });

                $('#grupo-horas-nhoras').change(function () {
                    actualizarInfoGrupoHoras();
                });

                $('#grupo-horas-min').change(function () {
                    actualizarInfoGrupoHoras();
                });

                function actualizarInfoGrupoHoras() {
                    var nhoras = $('#grupo-horas-nhoras').val();
                    var minutos = $('#grupo-horas-min').val();

                    var plural = (nhoras !== "1");

                    console.log(nhoras, minutos, plural);

                    $('#grupo-horas-info').html('Se agregará' + (plural ? "n" : "") + ' ' + (plural ? nhoras : "una") + ' hora' + (plural ? "s" : "") + ' de ' + minutos + ' minutos al listado de horas.');
                }
            });

            function actualizarHorasMasivas() {
                var tbody = $('#tbody-horas-masivas').empty();

                if (horas.length > 0) {
                    for (var i = 0; i < horas.length; i++) {
                        tbody.append('<tr index="' + i + '">' +
                            '<td style="background-color: ' + horas[i].color + ';">' + horas[i].nombre + '</td>' +
                            '<td style="text-align: center;">' + horas[i].hora_inicio + ' - ' + horas[i].hora_termino + '</td>' +
                            '<td style="text-align: center;">' +
                                '<span class="ui-icon ui-icon-trash delete-hora-masiva" style="cursor: pointer;"></span>' +
                            '</td>' +
                        '</tr>');
                    }

                    $('#tbl-horas-masivas').floatThead('reflow');

                    $('.delete-hora-masiva').click(function () {
                        var $this = $(this);

                        mensajes.confirmacion_sino("¿Desea quitar esta hora de la lista de horas a crear?", function () {
                            var index = parseInt($this.closest('tr').attr('index'));

                            horas.splice(index, 1);

                            actualizarHorasMasivas();
                        });
                    });
                }
                else {
                    tbody.append('<tr>' +
                        '<td colspan="3" style="text-align: center;">' +
                            'No hay horas a ser creadas' +
                        '</td>' +
                    '</tr>');
                }

                $('#nhoras-masiva').html('Horas a crear: <span class="bold">' + horas.length + '</span>');
            }
        }
    </script>
@endsection