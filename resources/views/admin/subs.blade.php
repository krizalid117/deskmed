@extends('layouts.app')

@section('title', '| Subscripciones')

@section('stylesheets')
    <style>
        .select2-search {
            display: none;
        }
    </style>
@endsection

@section('content')

    <div class="basic-form-container">

        <div style="display: flex; align-items: center; justify-content: flex-start;">
            <input type="text" id="filter-subs-search" placeholder="Filtrar..." style="width: 200px; height: 30px; padding-left: 5px;">
            <select id="filter-subs-state" style="width: 200px; height: 30px; margin-left: 10px;">
                <option value="all">Todas</option>
                <option value="1">Activas</option>
                <option value="2">Inactivas</option>
            </select>
            <button id="btn-add-sub" class="btn btn-success glyphicon glyphicon-plus" style="margin-left: auto;" title="Nueva subscripción"></button>
        </div>

        <br>
        <br>

        <table class="d-dtable">
            <thead>
                <tr>
                    <th>tstamp</th>
                    <th>tstamp_inicio_sub</th>
                    <th>tstamp_termino_sub</th>
                    <th style="width: 30px;">ID sub</th>
                    <th style="width: 50px;">ID usuario</th>
                    <th>Usuario</th>
                    <th style="width: 100px;">Plan</th>
                    <th style="width: 80px;">Precio mensual</th>
                    <th style="width: 100px;">Inicio</th>
                    <th style="width: 100px;">Término</th>
                    <th style="width: 70px;">Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

@endsection

@section('scripts')
    <script>
        var subs = eval({!! $subs !!});

        $(function () {
            var dtable = $('.d-dtable').DataTable({
                "processing": true,
                "serverSide": false,
                "data": subs,
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
                "order": [ [ 0, "desc" ] ],
                "bInfo": true,
                "columns": [
                    {
                        "data": "tstamp",
                        "visible": false,
                        "searchable": false
                    },
                    {
                        "data": "tstamp_inicio_sub",
                        "visible": false,
                        "searchable": false
                    },
                    {
                        "data": "tstamp_termino_sub",
                        "visible": false,
                        "searchable": false
                    },
                    {
                        "data": "id_sub",
                        "className": "d-dtable-center",
                        "searchable": false
                    },
                    {
                        "data": "id_usuario",
                        "className": "d-dtable-center",
                        "searchable": false
                    },
                    {
                        "data": "usuario_nombre_completo",
                        "searchable": true
                    },
                    {
                        "data": "nombre_plan",
                        "searchable": true
                    },
                    {
                        "data": "precio_mensual_plan",
                        "searchable": false
                    },
//                    {
//                        "data": "updated_at",
//                        "className": "d-dtable-center",
//                        "searchable": false,
//                        "orderable": true,
//                        "orderData": [0]
//                    },
                    {
                        "data": "inicio_subscripcion",
                        "className": "d-dtable-center",
                        "searchable": false,
                        "orderable": true,
                        "orderData": [1]
                    },
                    {
                        "data": "termino_subscripcion",
                        "className": "d-dtable-center",
                        "searchable": false,
                        "orderable": true,
                        "orderData": [2]
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

            $('#btn-add-sub').click(function () {

                sendPost('{{ route('admin.getusers') }}', {
                    _token: '{{ csrf_token() }}'
                }, function (res) {
                    $('<div class="add-sub-dialog">' +
                        '<fieldset class="fs-sub-user-selection">' +
                        '<legend class="bold">Seleccionar usuario</legend>' +
                            '<select class="sel-user-sub"></select>' +
                        '</fieldset>' +
                    '</div>').dialog({
                        title: "Agregar subscripción",
                        classes: { 'ui-dialog': 'dialog-responsive' },
                        width: 1000,
                        resizable: false,
                        draggable: true,
                        autoOpen: true,
                        modal: true,
                        escapeOnClose: true,
                        position: {my: "center top", at: "center top+70", of: window},
                        close: function () {
                            $('.add-sub-dialog').dialog('destroy').remove();
                        },
                        buttons: [
                            {
                                text: "Cerrar",
                                'class': 'btn',
                                click: function () {
                                    $('.add-sub-dialog').dialog("close");
                                }
                            },
                            {
                                text: 'Aceptar',
                                'class': 'btn btn-primary',
                                click: function () {

                                }
                            }
                        ]
                    });

                    for (var i = 0; i < res.usuarios.length; i++) {
                        var usuario = res.usuarios[i];

                        $('.sel-user-sub').append('<option value="' + usuario.usuario_id + '">' + usuario.usuario_nombre_completo + '</option>');
                    }

                    $('.sel-user-sub')//.select2()
                        .change(function () {
                            var $this = $(this);
                            var usuario = $.grep(res.usuarios, function (elem) {
                                return elem.usuario_id = $this.val();
                            })[0];

                            alert(usuario.usuario_nombre_completo);
                        });
                });
            });
        });
    </script>
@endsection