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

        .dialog-doctor-info > div {
            margin-bottom: 10px;
        }

        .dialog-doctor-info > div:nth-child(odd) {
            background-color: #f1f1f1;
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
                        "className": "d-dtable-center",
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
                        "className": "d-dtable-center",
                        "orderable": true,
                        "orderData": [2]
                    },
                    {
                        "data": "estado",
                        "className": "d-dtable-center",
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
            }).on('click', '.val-info', function () {
                var idUsuario = $(this).closest('tr').data('datos').id_usuario;

                openUserInfo(idUsuario);
            }).on('click', '.val-validate', function () {
                var datos = $(this).closest('tr').data('datos');
                var idVerificacion = datos.id;

                sendPost('{{ route('admin.getverificaciones') }}', {
                    _token: '{{ csrf_token() }}',
                    id: idVerificacion
                }, function (response) {
                    var solicitud = response.solicitud;
                    var verificaciones = eval(solicitud.verificaciones);

//                    if (verificaciones.length > 0) {
                        $('<div class="dialog-doctor-solicitud">' +
                            '<fieldset class="solicitud-user-info">' +
                                '<legend><span class="bold">Médico</span></legend>' +
                                '<div class="col-sm-4">' +
                                    '<span class="bold">Nombres:</span><br>' + datos.nombres +
                                '</div>' +
                                '<div class="col-sm-4">' +
                                    '<span class="bold">ID:</span><br>' + datos.id_usuario +
                                '</div>' +
                                '<div class="col-sm-4">' +
                                    '<span class="bold">Datos:</span><br><span class="glyphicon glyphicon-eye-open" onclick="openUserInfo(' + datos.id_usuario + ');" style="cursor: pointer;"></span>' +
                                '</div>' +
                            '</fieldset>' +
                            '<fieldset>' +
                                '<legend><span class="bold">Datos solicitud</span></legend>' +
                                '<div class="form-group col-sm-3">' +
                                    '<label for="solicitud-estoad" class="form-label">Estado:</label>' +
                                    '<select class="form-control" id="solicitud-estoad">' +
                                        '<option value="0" ' + (parseInt(solicitud.estado) === 0 ? "selected" : "") + '>Pendiente</option>' +
                                        '<option value="1" ' + (parseInt(solicitud.estado) === 1 ? "selected" : "") + '>Cursado</option>' +
                                        '<option value="2" ' + (parseInt(solicitud.estado) === 2 ? "selected" : "") + '>Cursado (No registra)</option>' +
                                        '<option value="3" ' + (parseInt(solicitud.estado) === 3 ? "selected" : "") + '>Cursado (Faltan datos)</option>' +
                                    '</select>' +
                                '</div>' +
                                '<div class="form-group col-sm-3">' +
                                    '<label for="solicitud-comentario" class="form-label">Comentario:</label>' +
                                    '<input type="text" id="solicitud-comentario" class="form-control" value="' + solicitud.comentario + '">' +
                                '</div>' +
                                '<div class="form-group col-sm-3">' +
                                    '<label for="solicitud-createdat" class="form-label">Creado el</label>' +
                                    '<input type="text" id="solicitud-createdat" class="form-control" value="' + solicitud.fecha_creacion + '">' +
                                '</div>' +
                                '<div class="form-group col-sm-3">' +
                                    '<label for="solicitud-updatedat" class="form-label">Último cambio</label>' +
                                    '<input type="text" id="solicitud-updatedat" class="form-control" value="' + solicitud.ultima_actualizacion + '">' +
                                '</div>' +
                            '</fieldset>' +
                        '</div>').dialog({
                            title: "Solicitud ID: " + idVerificacion,
                            classes: { 'ui-dialog': 'dialog-responsive' },
                            width: 1000,
                            resizable: false,
                            draggable: true,
                            autoOpen: true,
                            modal: true,
                            escapeOnClose: true,
                            close: function () {
                                $('.dialog-doctor-solicitud').dialog('destroy').remove();
                            },
                            buttons: [
                                {
                                    text: "Cerrar",
                                    'class': 'btn',
                                    click: function () {
                                        $('.dialog-doctor-solicitud').dialog("close");
                                    }
                                }
                            ]
                        });
//                    }
//                    else {
//                        mensajes.alerta("Ya no existe esta solicitud. Presione \"Aceptar\" para recargar la página.", "Solicitud inexistente", function () {
//                            location.reload();
//                        });
//                    }
                });
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

        function openUserInfo(idUsuario) {
            sendPost('{{ route('admin.getdoctorinfo') }}', {
                _token: '{{ csrf_token() }}',
                id: idUsuario
            }, function (response) {
                var doctor = response.doctor;

                $('<div class="dialog-doctor-info">' +
                    '<div class="col-sm-4">' +
                    '<span class="bold">Nombres:</span><br>' + doctor.nombres +
                    '</div>' +
                    '<div class="col-sm-4">' +
                    '<span class="bold">Apellidos:</span><br>' + doctor.apellidos +
                    '</div>' +
                    '<div class="col-sm-4">' +
                    '<span class="bold">Fecha creación cta.:</span><br>' + doctor.fecha_registro +
                    '</div>' +
                    '<div class="col-sm-4">' +
                    '<span class="bold">e-mail:</span><br>' + doctor.email +
                    '</div>' +
                    '<div class="col-sm-4">' +
                    '<span class="bold">Fecha nacimiento:</span><br>' + doctor.fecha_nacimiento +
                    '</div>' +
                    '<div class="col-sm-4">' +
                    '<span class="bold">Sexo:</span><br>' + doctor.sexo +
                    '</div>' +
                    '<div class="col-sm-4">' +
                    '<span class="bold">Tipo identificador:</span><br>' + doctor.tipo_identificador +
                    '</div>' +
                    '<div class="col-sm-4">' +
                    '<span class="bold">Identificador:</span><br>' + (doctor.tipo_identificador !== 1 ? doctor.identificador : formatearRut(doctor.identificador)) +
                    '</div>' +
                    '<div class="col-sm-4">' +
                    '<span class="bold" title="Título (según usuario)">Título (S.U.):</span><br>' + doctor.titulo_segun_usuario +
                    '</div>' +
                    '<div class="col-sm-4">' +
                    '<span class="bold" title="Especialidad (según usuario)">Especialidad (S.U.):</span><br>' + doctor.especialidad_segun_usuario +
                    '</div>' +
                    '<div class="col-sm-4">' +
                    '<span class="bold" title="Institución habilitante (según usuario)">Instit. habil. (S.U.):</span><br>' + doctor.institucion_habilitante_segun_usuario +
                    '</div>' +
                    '<div class="col-sm-4">' +
                    '<span class="bold" title="N° registro (según usuario)">N° registro (S.U.):</span><br>' + doctor.nregistro_segun_usuario +
                    '</div>' +
                    '<div class="col-sm-4">' +
                    '<span class="bold" title="Fecha registro (según usuario)">Fecha registro (S.U.):</span><br>' + doctor.fecha_registro_segun_usuario +
                    '</div>' +
                    '<div class="col-sm-4">' +
                    '<span class="bold" title="Antecedente del título (según usuario)">Antecedente título (S.U.):</span><br>' + doctor.antecedente_titulo_segun_usuario +
                    '</div>' +
                    '<div class="col-sm-4">' +
                    '<span class="bold">Última Actualización:</span><br>' + doctor.ultima_actualizacion +
                    '</div>' +
                '</div>').dialog({
                    title: "Doctor ID: " + idUsuario,
                    classes: {'ui-dialog': 'dialog-responsive'},
                    width: 1000,
                    resizable: false,
                    draggable: true,
                    autoOpen: true,
                    modal: true,
                    escapeOnClose: true,
                    close: function () {
                        $('.dialog-doctor-info').dialog('destroy').remove();
                    },
                    buttons: [
                        {
                            text: "Cerrar",
                            'class': 'btn',
                            click: function () {
                                $('.dialog-doctor-info').dialog("close");
                            }
                        }
                    ]
                });
            });
        }

    </script>
@endsection