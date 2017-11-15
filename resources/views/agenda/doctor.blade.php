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
            height: 40px;
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

        .table > thead > tr > th:first-child {
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
            z-index: 2;
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
            height: 50px;
            padding: 0 !important;
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
                            <th data-day="1" style="width: 13.5%;">Lunes</th>
                            <th data-day="2" style="width: 13.5%;">Martes</th>
                            <th data-day="3" style="width: 13.5%;">Miércoles</th>
                            <th data-day="4" style="width: 13.5%;">Jueves</th>
                            <th data-day="5" style="width: 13.5%;">Viernes</th>
                            <th data-day="6" style="width: 13.5%;">Sábado</th>
                            <th data-day="0" style="width: 13.5%;">Domingo</th>
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
            <button class="btn">Action</button>
        </div>

    </div>
@endsection


@section('scripts')
    <script type="text/javascript">

        $(function () {
            var tblAgenda = $('.tbl-agenda');
            var agendaContainer = $('.agenda-container');

            @if($mode === "weekly")

                var weekpicker = $('.week-picker');

                var selectCurrentWeek = function (monthChange) {
                    setTimeout(function () {
                        weekpicker.find('.ui-datepicker-current-day a').addClass('ui-state-active');

                        if (!weekpicker.hasClass('hidden') && !monthChange) {
                            weekpicker.addClass('hidden');

                            var icon = $('.weekly-labels-button').find('.ui-icon');

                            icon.toggleClass('ui-icon-triangle-1-s');
                            icon.toggleClass('ui-icon-triangle-1-n');
                        }
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
                    var startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 1);
                    var endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 7);
                    var dateFormat = $.datepicker._defaults.dateFormat;

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
                updateEndTime($(this), function () {
                    ocultarFilasHorario();
                });
            });

            $(window).resize(function () {
                var extra = (document.documentElement.clientWidth <= 767) ? 15 : 0;

                $('.agenda-wrapper').css('max-height', (agendaContainer.outerHeight() - extra) + 'px');
            }).resize();

            updateEndTime($('.agenda-rango-hora.start'), function () {
                ocultarFilasHorario();
            });

            $('.weekly-agenda-row[data-hora="11:00"]').children('.weekly-agenda-day[data-dia="4"]').append('<div style="width: calc(100% - 4px); position: absolute; left: 2px; top: 25%; height: 200%; cursor: pointer; z-index: 1;">' +
                '<div style="background-color: green; height: 100%; width: 100%; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px;">' +
                    'ASDASD' +
                '</div>' +
            '</div>');
        });

        function loadAgenda() {
            mensajes.loading_open();

            var tbody = $('.tbl-agenda').children('tbody');

            @if ($mode === "daily")

            @elseif ($mode === "weekly")



            @elseif ($mode === "monthly")

            @endif

            mensajes.loading_close();
        }

        function ocultarFilasHorario() {
            var horaStart = parseInt($('.agenda-rango-hora.start').val().split(':')[0]);
            var horaEnd = parseInt($('.agenda-rango-hora.end').val().split(':')[0]);

            console.log(horaStart, horaEnd);

            $('.weekly-agenda-row').each(function () {
                var $this = $(this);
                var hora = parseInt($this.data('hora').split(':')[0]);

                console.log(hora);

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

        function updateEndTime($this, callback) {

            if ($this.hasClass('start')) {
                var $end = $('.agenda-rango-hora.end');

                if ($end.timepicker('getTime') < $this.timepicker('getTime')) {
                    $end.timepicker('setTime', $this.timepicker('getTime'));
                }

                $end.timepicker('option', { 'minTime': $this.val() });
            }

            if (callback) {
                callback();
            }
        }
    </script>
@endsection