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

        .tbl-agenda-weekly > thead > tr > th {
            background-color: var(--normal-background-color);
            height: 40px;
        }

        .tbl-agenda-weekly > thead > tr > th:not(:first-child) {
            padding: 0 !important;
            text-align: center;
            border-left: 1px solid #ddd;
        }

        .tbl-agenda-weekly > tbody > tr > td:not(:first-child) {
            border-left: 1px solid #ddd;
            text-align: center;
            height: 50px;
            padding: 0 !important;
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
            padding: 2px 2px 0 0 !important;
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
        }

        .weekly-labels .weekly-labels-button:hover {
            border: 1px solid #ddd;

            -webkit-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.55);
            -moz-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.55);
            box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.55);
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
                        <tbody></tbody>
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

            $('.agenda-wrapper').css('max-height', agendaContainer.height() + 'px');

            @if($mode === "weekly")

            var weekpicker = $('.week-picker');
            var startDate;
            var endDate;

            var selectCurrentWeek = function() {
                setTimeout(function () {
                    $('.week-picker').find('.ui-datepicker-current-day a').addClass('ui-state-active');

                    if (!weekpicker.hasClass('hidden')) {
                        weekpicker.addClass('hidden');
                    }
                }, 1);
            };

            weekpicker.datepicker({
                showOtherMonths: true,
                selectOtherMonths: true,
                onSelect: function(dateText, inst) {
                    var date = $(this).datepicker('getDate');
                    startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() +1);
                    endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 7);
                    var dateFormat = inst.settings.dateFormat || $.datepicker._defaults.dateFormat;

                    $('#startDate').text($.datepicker.formatDate(dateFormat, startDate, inst.settings));
                    $('#endDate').text($.datepicker.formatDate(dateFormat, endDate, inst.settings));

                    selectCurrentWeek();
                },
                beforeShowDay: function(date) {
                    var cssClass = '';

                    if(date >= startDate && date <= endDate) {
                        cssClass = 'ui-datepicker-current-day';
                    }

                    return [true, cssClass];
                },
                onChangeMonthYear: function(year, month, inst) {
                    selectCurrentWeek();
                }
            }).datepicker('hide');

            $(document).on('mousemove', '.week-picker .ui-datepicker-calendar tr', function() { $(this).find('td a').addClass('ui-state-hover'); });
            $(document).on('mouseleave', '.week-picker .ui-datepicker-calendar tr', function() { $(this).find('td a').removeClass('ui-state-hover'); });


            $('.ui-datepicker-today').click();

            $('.weekly-labels-button').click(function () {
               weekpicker.toggleClass('hidden');
            });

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
                var $this = $(this);

                if ($this.hasClass('start')) {
                    var $end = $('.agenda-rango-hora.end');

                    if ($end.timepicker('getTime') < $this.timepicker('getTime')) {
                        $end.timepicker('setTime', $this.timepicker('getTime'));
                    }

                    $end.timepicker('option', { 'minTime': $this.val() });
                }

                loadAgenda();
            }).trigger('changeTime');

            $(window).resize(function () {
                $('.agenda-wrapper').css('max-height', agendaContainer.height() + 'px');
            }).resize();
        });

        function loadAgenda() {
            mensajes.loading_open();

            var tbody = $('.tbl-agenda').children('tbody').empty();

            @if ($mode === "daily")

            @elseif ($mode === "weekly")

            var horaStart = parseInt($('.agenda-rango-hora.start').val().split(':')[0]);
            var horaEnd = parseInt($('.agenda-rango-hora.end').val().split(':')[0]);

            for (var i = horaStart; i <= horaEnd; i++) {
                tbody.append('<tr>' +
                    '<td class="agenda-horario-fila">' + i + ':00</td>' +
                    '<td>l</td>' +
                    '<td>m</td>' +
                    '<td>m</td>' +
                    '<td>j</td>' +
                    '<td>v</td>' +
                    '<td>s</td>' +
                    '<td>d</td>' +
                '</tr>');
            }

            @elseif ($mode === "monthly")

            @endif

            mensajes.loading_close();
        }
    </script>
@endsection