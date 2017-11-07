@extends('layouts.app')

@section('title', '| Cuenta')

@section('stylesheets')
    <style>
        .val-actions {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .val-actions > span {
            width: 40%;
            text-align: center;
            cursor: pointer;
        }
    </style>
@endsection

@section('content')

    <div class="basic-form-container">

        <input type="text" id="filter-validations-search" placeholder="Filtrar..." style="width: 200px; height: 30px; padding-left: 5px;">
        <select id="filter-validations-type" style="width: 200px; height: 30px;">
            <option value="all">Todos</option>
            <option value="0">Pendientes</option>
            <option value="1">Cursados</option>
            <option value="2">Cursados (No registra)</option>
            <option value="3">Cursados (Faltan datos)</option>
        </select>
        <br>
        <br>

        <table class="d-dtable">
            <thead>
                <tr>
                    <th style="width: 30px;">ID</th>
                    <th>Nombre</th>
                    <th>tstamp</th>
                    <th style="width: 150px;">Fecha/hora</th>
                    <th style="width: 150px;">Estado</th>
                    <th style="width: 70px;">Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript">
        var validations = JSON.parse('{!! $validations !!}');

        $(function () {
            var dtable = $('.d-dtable').DataTable({
                "processing": true,
                "serverSide": false,
                "data": validations,
//                "data": [],
                "language": {
                    "lengthMenu": 'Mostrar <input type="text" value="10"> registros por página',
                    "processing": 'Cargando...',
                    "zeroRecords": "La búsqueda no entregó ningún resultado",
                    "info": "Mostrando <b>_START_</b> a <b>_END_</b> de <b>_TOTAL_</b> registros",
                    "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                    "infoFiltered": " - filtrando a partir de _MAX_ registros",
                    "decimal": ",",
                    "thousands": ".",
                    "paginate": {
                        "first":      "|<",
                        "last":       ">|",
                        "next":       ">",
                        "previous":   "<"
                    }
                },
                "pagingType": "full_numbers",
                "dom": 'irtp',
                "pageLength": 15,
                "order": [ [ 3, "desc" ] ],
                "bInfo": true,
                "columns": [
                    {
                        "data": "id",
                        "searchable": false
                    },
                    {
                        "data": "nombre_completo",
                        "searchable": true
                    },
                    {
                        "data": "tstamp",
                        "visible": false,
                        "searchable": false
                    },
                    {
                        "data": "updated_at",
                        "orderable": true,
                        "orderData": [2]
                    },
                    {
                        "data": "estado",
                        "render": function (data) {
                            var txt = "Pendiente";

                            switch (data) {
                                case 1:
                                    txt = "<span style=\"color: green;\">Cursado</span>";
                                    break;
                                case 2:
                                    txt = "<span style=\"color: red;\">Cursado (No registra)</span>";
                                    break;
                                case 3:
                                    txt = "<span style=\"color: yellow;\">Cursado (Faltan datos)</span>";
                                    break;
                            }

                            return txt;
                        }
                    },
                    {
                        "data": null,
                        "render": function () {
                            return '<div class="val-actions"><span class="glyphicon glyphicon-eye-open val-info" title="Ver datos del doctor"></span><span class="glyphicon glyphicon-ok val-validate" title="Validar"></span></div>';
                        }
                    }
                ],
                rowCallback: function (row, data) {
                    $(row).data('datos', data);
                }
            });

            $('#filter-validations-search').keyup(function () {
                dtable.search(this.value).draw();
            });

            $('#filter-validations-type').change(function () {
                var dt = $('.d-dtable').dataTable();

                var getCondition = function (dato, value) {
                    switch (value) {
                        case "1":
                            return parseInt(dato) === 1;
                            break;
                        case "2":
                            return parseInt(dato) === 2;
                            break;
                        case "3":
                            return parseInt(dato) === 3;
                            break;
                        default:
                            return true;
                            break;
                    }
                };

                mensajes.loading_open();

                dt.fnClearTable();

                for (var i = 0; i < validations.length; i++) {
                    if (getCondition(validations[i].estado, $(this).val())) {
                        dt.fnAddData(validations[i]);
                    }
                }

                mensajes.loading_close();
            });
        });

    </script>
@endsection