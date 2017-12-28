@extends('layouts.app')

@section('title', '| Subscripciones')

@section('stylesheets')
    <style>
        .select2-search {
            display: none;
        }

        .new-sub-info {
            color: var(--secondary-background-color) !important;
        }

        .new-sub-info > span {
            display: inline-block;
            margin-right: 10px;
        }

        .val-actions {
            display: flex;
            align-items: center;
            justify-content: space-around;
        }

        .val-actions > span {
            flex: 0 0 auto;
            text-align: center;
            cursor: pointer;
        }
    </style>
@endsection

@section('content')

    <div class="basic-form-container">

        <div class="admin-tabs">
            <ul>
                <li><a href="#admin-subs">Subscripciones</a></li>
                <li><a href="#admin-plans">Planes</a></li>
            </ul>
            <div id="admin-subs">
                <div style="display: flex; align-items: center; justify-content: flex-start;">
                    <input type="text" id="filter-subs-search" placeholder="Filtrar..." style="width: 200px; height: 30px; padding-left: 5px;">
                    <select id="filter-subs-state" style="width: 200px; height: 30px; margin-left: 10px;">
                        <option value="0" {{ (intval($tipo) === 0 ? "selected" : "") }}>Todas</option>
                        <option value="1" {{ (intval($tipo) === 1 ? "selected" : "") }}>Activas</option>
                        <option value="2" {{ (intval($tipo) === 2 ? "selected" : "") }}>Inactivas</option>
                    </select>
                    <button id="btn-add-sub" class="btn btn-success glyphicon glyphicon-plus" style="margin-left: auto;" title="Nueva subscripción"></button>
                </div>

                <br>
                <br>

                <table class="d-dtable">
                    <thead>
                    <tr>
                        <th>tstamp</th>
                        <th>tstamp inicio sub</th>
                        <th>tstamp termino sub</th>
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
            <div id="admin-plans">

            </div>
        </div>

    </div>

@endsection

@section('scripts')
    <script>
        var subs = eval({!! $subs !!});

        $(function () {
            $('.admin-tabs').tabs();

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
                        "searchable": false,
                        "className" : "d-dtable-right",
                        render: function (data) {
                            return '$' + data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        }
                    },
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
                            return '<div class="val-actions">' +
                                '<span class="glyphicon glyphicon-retweet sub-extender" title="Extender o renovar subscripción"></span>' +
//                                '<span class="glyphicon glyphicon-"></span>' +
//                                '<span class="glyphicon glyphicon-"></span>' +
//                                '<span class="glyphicon glyphicon-"></span>' +
                            '</div>';
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
                    var usuarioSeleccionado = null;

                    $('<div class="add-sub-dialog">' +
                        '<fieldset class="fs-sub-user-selection">' +
                            '<legend class="bold">Seleccionar usuario</legend>' +
                            '<div class="form-group">' +
                                '<select class="sel-user-sub form-control">' +
                                    '<option value="0">Seleccione usuario</option>' +
                                '</select>' +
                            '</div>' +
                            '<a href="#" class="new-sub-info" style="display: none;"><span class="glyphicon glyphicon-eye-open"></span>Ver info usuario</a>' +
                        '</fieldset>' +
                        '<fieldset class="fs-sub-plan-selection" style="display: none;">' +
                            '<legend class="bold">Seleccionar plan</legend>' +
                            '<div class="form-group">' +
                                '<select class="sel-plan form-control">' +
                                    '<option value="0">Seleccione plan</option>' +
                                '</select>' +
                            '</div>' +
                        '</fieldset>' +
                        '<fieldset class="fs-sub-period-selection" style="display: none;">' +
                            '<legend class="bold">Seleccionar período</legend>' +
                            '<div class="col-sm-6">' +
                                '<div class="form-group">' +
                                    '<label for="fecha-inicio-sub" class="form-label">Inicio subscripción: </label>' +
                                    '<input type="text" id="fecha-inicio-sub" class="sel-fecha-inicio-sub form-control" value="{{ date('d-m-Y') }}" readonly>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-sm-6">' +
                                '<div class="form-group">' +
                                    '<label for="duracion-sub" class="form-label">Duración (en meses): </label>' +
                                    '<select id="duracion-sub" class="sel-duracion-sub form-control">' +
                                        '<option value="1">1</option>' +
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
                                        '<option value="21">21</option>' +
                                        '<option value="22">22</option>' +
                                        '<option value="23">23</option>' +
                                        '<option value="24">24</option>' +
                                    '</select>' +
                                '</div>' +
                            '</div>' +
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
                        position: { my: "center top", at: "center top+70", of: window },
                        close: function () {
                            $('.add-sub-dialog').dialog('destroy').remove();
                        },
                        buttons: [
                            {
                                text: "Cancelar",
                                'class': 'btn',
                                click: function () {
                                    $('.add-sub-dialog').dialog("close");
                                }
                            },
                            {
                                text: 'Aceptar',
                                'class': 'btn btn-primary btn-guardar-sub',
                                click: function () {
                                    var error = "";

                                    if ($('.sel-user-sub').val() === "0") {
                                        error = "Debe seleccionar un usuario.";
                                    }
                                    else if ($('.sel-plan').val() === "0") {
                                        error = "Debe seleccionar un plan.";
                                    }
                                    else if ($('.sel-fecha-inicio-sub').val() === "") {
                                        error = "Debe seleccionar una fecha.";
                                    }
                                    else if (parseInt($('.sel-duracion-sub').val()) < 0 && parseInt($('.sel-duracion-sub').val()) > 24) {
                                        error = "La cantidad de meses debe ser superior a cero e inferior o igual a 24.";
                                    }

                                    if (error !== "") {
                                        mensajes.alerta(error, "Error");
                                    }
                                    else {
                                        mensajes.confirmacion_sino('Se va a guardar una subscripción para el usuario <span class="bold">' + usuarioSeleccionado.usuario_nombre_completo + '</span>' +
                                            ', con plan <span class="bold">' + $('.sel-plan').find('option').filter(':selected').text() + '</span>' +
                                            ' desde el <span class="bold">' + $('.sel-fecha-inicio-sub').val() + '</span>' +
                                            ' durante <span class="bold">' + $('.sel-duracion-sub').val() + '</span> mes' + (parseInt($('.sel-duracion-sub').val()) > 1 ? 'es' : '') + '.' +
                                            '<br><br>Se agregará un pago por $' + (parseInt($('.sel-plan').find('option').filter(':selected').attr('precio')) * parseInt($('.sel-duracion-sub').val())).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '.-' +
                                        '<br><br>¿Desea continuar?', function () {
                                            sendPost('{{ route('admin.savesub') }}', {
                                                _token: '{{ csrf_token() }}',
                                                id_usuario: usuarioSeleccionado.usuario_id,
                                                id_plan: $('.sel-plan').val(),
                                                fecha_desde:  $('.sel-fecha-inicio-sub').val(),
                                                nmeses: $('.sel-duracion-sub').val()
                                            }, function () {
                                                mensajes.alerta('Subscripción agregada correctamente.', 'Subscripción', function () {
                                                    location.reload();
                                                });
                                            });
                                        });
                                    }
                                }
                            }
                        ]
                    });

                    $('.btn-guardar-sub').prop('disabled', true);

                    $('.sel-fecha-inicio-sub').datepicker({
                        "changeYear": true,
                        "changeMonth": true,
                        "maxDate": '{{ date('d-m-Y') }}'
                    });

                    //usuarios
                    (function () {
                        for (var i = 0; i < res.usuarios.length; i++) {
                            var usuario = res.usuarios[i];

                            $('.sel-user-sub').append('<option value="' + usuario.usuario_id + '">' + usuario.usuario_nombre_completo + '</option>');
                        }
                    })();

                    //planes
                    (function () {
                        for (var i = 0; i < res.planes.length; i++) {
                            var plan = res.planes[i];

                            $('.sel-plan').append('<option value="' + plan.id + '" precio="' + plan.precio_mensual + '">' + plan.nombre + ' (Mensual: $' + plan.precio_mensual.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ')' + '</option>');
                        }
                    })();

                    $('.new-sub-info').click(function () {
                        sendPost('{{ route('admin.getdoctorinfo') }}', {
                            _token: '{{ csrf_token() }}',
                            id: usuarioSeleccionado.usuario_id
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
                                title: "Doctor ID: " + usuarioSeleccionado.usuario_id,
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
                    });

                    //Selección de usuario
                    $('.sel-user-sub')//.select2()
                        .change(function () {
                            var $this = $(this);

                            if ($this.val() !== "0") {

                                for (var i = 0; i < res.usuarios.length; i++) {
                                    if (res.usuarios[i].usuario_id === parseInt($this.val())) {
                                        usuarioSeleccionado = res.usuarios[i];

                                        $('.new-sub-info').show();
                                        $('.fs-sub-plan-selection').show();

                                        break;
                                    }
                                }
                            }
                            else {
                                $('.new-sub-info').hide();
                                $('.fs-sub-plan-selection').hide();
                                $('.fs-sub-period-selection').hide();
                                $('.sel-plan').val(0);
                                $('.sel-fecha-inicio-sub').val('{{ date('d-m-Y') }}');
                                $('.sel-duracion-sub').val(1);

                                $('.btn-guardar-sub').prop('disabled', true);
                            }
                        });

                    $('.sel-plan').change(function () {
                        var $this = $(this);

                        if ($this.val() !== "0") {
                            $('.fs-sub-period-selection').show();
                            $('.btn-guardar-sub').prop('disabled', false);
                        }
                        else {
                            $('.fs-sub-period-selection').hide();
                            $('.btn-guardar-sub').prop('disabled', true);
                        }
                    });
                });
            });

            $('#admin-subs').on('click', '.sub-extender', function () {
                var data = $(this).closest('tr').data('datos');

                $('<div class="dlg-sub-extender">' +
                    '<div class="form-group">' +
                        '<label for="inp-sub-extender" class="form-label">Meses a extender: </label>' +
                        '<select type="text" id="inp-sub-extender" class="form-control">' +
                            '<option value="1">1</option>' +
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
                            '<option value="21">21</option>' +
                            '<option value="22">22</option>' +
                            '<option value="23">23</option>' +
                            '<option value="24">24</option>' +
                        '</select>' +
                    '</div>' +
                '</div>').dialog({
                    title: "Extender o renovar subscripción",
                    classes: { 'ui-dialog': 'dialog-responsive' },
                    width: 500,
                    resizable: false,
                    draggable: true,
                    autoOpen: true,
                    modal: true,
                    escapeOnClose: true,
                    close: function () {
                        $('.dlg-sub-extender').dialog('destroy').remove();
                    },
                    buttons: [
                        {
                            text: "Cancelar",
                            'class': 'btn',
                            click: function () {
                                $('.dlg-sub-extender').dialog("close");
                            }
                        },
                        {
                            text: "Guardar",
                            'class': 'btn btn-primary',
                            click: function () {
                                mensajes.confirmacion_sino('Se agregarán <span class="bold">' + $('#inp-sub-extender').val() + '</span> meses a la subscripción.' +
                                    '<br><br>Se agregará un pago por $' + (parseInt(data.precio_mensual_plan) * parseInt($('#inp-sub-extender').val())).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '.-' +
                                    '<br><br>¿Desea continuar?', function () {
                                    sendPost('{{ route('admin.extendsub') }}', {
                                        _token: '{{ csrf_token() }}',
                                        id_sub: data.id_sub,
                                        nmeses: $('#inp-sub-extender').val()
                                    }, function () {
                                        mensajes.alerta("Subscripción extendida correctamente.", "Subcripción", function () {
                                            location.reload();
                                        });
                                    });
                                });
                            }
                        }
                    ]
                });
            });

            $('#filter-subs-state').change(function () {
                window.location = '/admin/subs/' + $(this).val();
            });
        });
    </script>
@endsection