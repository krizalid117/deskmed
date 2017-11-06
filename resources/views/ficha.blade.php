<?php

use \App\Http\Controllers\UsuarioController;
use \App\Http\Controllers\GlobalController;
use \App\Usuario;
use \App\AntecedentesFamiliaresOpciones;
use \App\UsuarioAntecedentesFamiliares;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

$usuarioDB = Usuario::find($id);

$anios = GlobalController::edad_anios($usuarioDB->fecha_nacimiento);
$sexo =  $usuarioDB->sexo()->first();

$usuarioAntFamId = []; //id de antecedentes familiares ya seteados por el usuario
$nespecificaciones = 0;

foreach ($afu as $a) {
    $usuarioAntFamId[] = $a->id;
}

$titulo = ($anios > 17 ? $sexo->alias_adulto : $sexo->alias_infantil . ". " . GlobalController::edad($usuarioDB->fecha_nacimiento));

?>

@extends('layouts.app')

@section('title', '| Ficha de salud')

@section('stylesheets')
    <style type="text/css">
        .ficha-header {
            margin-bottom: 15px;
        }

        .ant-fam-list {
            list-style: none;
            display: flex;
            /*max-width: 600px;*/
            width: 100%;
            margin: 0 0 15px 0;
            flex-flow: row wrap;

            justify-content: space-between;
        }

        .ant-fam-list > li {
            flex: 0 0 215px;
            margin-bottom: 5px;

            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .ant-fam-list > li > div > input[type="checkbox"] {
            flex: 0 0 auto;
            margin: 0 3px 0 !important;
            cursor: pointer;
        }

        .ant-fam-list > li > div > label {
            flex: 1 1 auto;
            margin-left: 3px;
            /*margin-bottom: 0 !important;*/
            cursor: pointer;
        }

        .ant-fam-list.ant-fam-list-esp {
            justify-content: flex-start;
            margin-bottom: 0;
        }

        .ant-fam-list.ant-fam-list-esp > li:not(:last-child) {
            margin-right: 10px;
        }

        input[id^="ant-fam-esp-txt-"] {
            width: 90% !important;
        }

        .pretty i {
            margin-top: 3px;
        }

        .nf-actions {
            cursor: pointer;
        }

        .pp-title-sub {
            text-align: center;
            display: none;
        }

        .select2-search__field {
            width: 100% !important;
        }

        .pretty > input[type='checkbox'][disabled] + label {
             opacity: .8 !important;
        }

        @media (max-width: 767px) {
            .pp-title-sub {
                display: block;
            }
        }
    </style>
@endsection

@section('content')

    <div class="professional-profile-header">
        <div class="pp-pic-container">
            <img src="{{ URL::to('profilePics/' . UsuarioController::getProfilePic($usuarioDB->profile_pic_path, $usuarioDB->id_sexo)) }}" class="img-circle">
        </div>
        <div class="pp-presentation paciente">
            <div class="pp-fold paciente"></div>
            <div class="pp-name font-title-normal-on-xs" title="Nombre completo del profesional">
                {{ $usuarioDB->nombres . ' ' . $usuarioDB->apellidos }}
            </div>
            <div class="pp-title" title="Sexo, edad.">
                {{ $titulo }}
            </div>
        </div>
    </div>

    @if($isOwnUser || !is_null($usuarioDB->doctors()->where('id_usuario_doctor', $usuario["id"])->first()))

        <div class="basic-form-container">
            <p class="pp-title-sub">{{ $titulo }}</p>
            <fieldset class="fs-collapsable" data-collapsed="false">
                <legend class="fs-collapsable-title"><span class="ui-icon ui-icon-minus"></span>Antecedentes familiares</legend>
                <div class="fs-collapsable-content">
                    <div class="ficha-header">Indique las enfermedades que haya padecido <span class="bold">algún familiar cercano</span>:</div>
                    <ul class="ant-fam-list">
                        @foreach ($ant_fam_op as $afo)
                            <li>
                                <div class="pretty o-danger curvy">
                                    <input type="checkbox" data-ant-fam="{{ $afo->id }}" id="ant-fam-op-{{ $afo->id }}" data-especifica="{{ ($afo->necesita_especificacion ? "true" : "false") }}" {{ (in_array($afo->id, $usuarioAntFamId) !== false ? "checked" : "") }} {{ ($isOwnUser ? "" : "disabled") }}>
                                    <label for="ant-fam-op-{{ $afo->id }}" class="bold"><i class="glyphicon glyphicon-ok"></i> {{ $afo->nombre }}</label>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <div class="ant-fam-esp panel panel-danger {{ (count($usuarioAntFamId) > 0 ? "" : "hidden") }}">
                        <div class="panel-heading">Especificaciones <span class="ui-icon ui-icon-help deskmed-icon-help" title="Por favor, especifique el parentesco del familiar que sufre o sufrió la enfermedad. Si es necesario, especificar el tipo del padecimiento. Ejemplo: Cáncer (Tía, a la piel)."></span></div>
                        <div class="panel-body">
                            <ul class="ant-fam-list ant-fam-list-esp">
                                @foreach ($afu as $a)
                                    <li class="ant-fam-esp-item" id="ant-fam-esp-item-{{ $a->id }}">
                                        <div class="form-group">
                                            <label class="form-label" for="ant-fam-esp-txt-{{ $a->id }}">{{ $a->nombre }}</label>
                                            <input type="text" class="form-control" us-ant-fam="{{ $a->id_usuario_antecedente_familiar }}" id="ant-fam-esp-txt-{{ $a->id }}" value="{{ $a->especificacion }}" {{ ($isOwnUser ? "" : "readonly") }}>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset class="fs-collapsable" data-collapsed="false">
                <legend class="fs-collapsable-title"><span class="ui-icon ui-icon-minus"></span>Núcleo familiar</legend>
                <div class="fs-collapsable-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-condensed">
                            <thead>
                                <tr>
                                    <th>Parentesco</th>
                                    <th>Edad</th>
                                    <th>Estado salud</th>
                                    <th>Edad al morir</th>
                                    <th>Causa de muerte</th>
                                    @if ($isOwnUser)
                                        <th>Acciones</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($nucleoFamiliar) > 0)
                                    @foreach($nucleoFamiliar as $nf)
                                        <tr data-datos="{{ json_encode([
                                            "id" => $nf->id,
                                            "id_parentesco" => $nf->id_parentesco,
                                            "edad" => $nf->edad,
                                            "id_estado_salud" => $nf->id_estado_salud,
                                            "edad_muerte" => $nf->edad_muerte,
                                            "causa_muerte" => $nf->causa_muerte,
                                        ]) }}" class="{{ ($nf->id_estado_salud === 6 ? "danger" : "") }}">
                                            <td>{{ $nf->nombre_parentesco }}</td>
                                            <td>{{ $nf->edad }}</td>
                                            <td>{{ $nf->nombre_estado }}</td>
                                            <td>{!! ($nf->id_estado_salud === 6) ? $nf->edad_muerte : '<span class="glyphicon glyphicon-ban-circle">' !!}</td>
                                            <td>{!! ($nf->id_estado_salud === 6) ? $nf->causa_muerte : '<span class="glyphicon glyphicon-ban-circle">' !!}</td>
                                            @if ($isOwnUser)
                                                <td style="width: 50px; text-align: center;">
                                                    <span class="ui-icon ui-icon-pencil nf-actions nf-action-edit" title="Editar este integrante"></span>
                                                    <span class="ui-icon ui-icon-trash nf-actions nf-action-delete" title="Remover este integrante de la lista"></span>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class=""><td colspan="6" style="text-align: center;">No ha agregado integrantes a su núcleo familiar</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @if ($isOwnUser)
                        <div style="text-align: right;">
                            <button class="btn btn-success btn-xs" id="btn-add-integrante">
                                Agregar integrante
                            </button>
                        </div>
                    @endif
                </div>
            </fieldset>
            <fieldset class="fs-collapsable" data-collapsed="false">
                <legend class="fs-collapsable-title"><span class="ui-icon ui-icon-minus"></span>Antecedentes personales</legend>
                <div class="fs-collapsable-content">
                    <div class="form-group">
                        <label for="ant-per-enf-act" class="form-label" style="font-weight: normal;">Indique a continuación las condiciones médicas que <span class="bold">tenga actualmente</span>:</label>
                        <select class="form-control" id="ant-per-enf-act" style="width: 100% !important;" multiple="multiple" {{ ($isOwnUser ? "" : "disabled") }}>
                            @foreach ($enfermedades as $enf)
                                <option value="{{ $enf->id }}" {{ (in_array($enf->id, $enfermedadesActuales) !== false ? "selected" : "") }}>{{ $enf->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ant-per-act-etc" class="form-label" style="font-weight: normal;">Otros comentarios sobre las condiciones médicas que <span class="bold">tenga actualmente</span>:</label>
                        <textarea class="form-control txta-vert" id="ant-per-act-etc" data-original="{{ $usuarioDB->comentario_condiciones_actuales }}" {{ ($isOwnUser ? "" : "readonly") }}>{{ $usuarioDB->comentario_condiciones_actuales }}</textarea>
                    </div>
                    <hr class="hr2">
                    <div class="form-group">
                        <label for="ant-per-enf-hist" class="form-label" style="font-weight: normal;">Indique a continuación las condiciones médicas que <span class="bold">haya tenido</span>:</label>
                        <select class="form-control" id="ant-per-enf-hist" style="width: 100% !important;" multiple="multiple" {{ ($isOwnUser ? "" : "disabled") }}>
                            @foreach ($enfermedades as $enf)
                                <option value="{{ $enf->id }}" {{ (in_array($enf->id, $enfermedadesHistoricas) !== false ? "selected" : "") }}>{{ $enf->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ant-per-hist-etc" class="form-label" style="font-weight: normal;">Otros comentarios sobre las condiciones médicas que <span class="bold">haya tenido</span>:</label>
                        <textarea class="form-control txta-vert" id="ant-per-hist-etc" data-original="{{ $usuarioDB->comentario_condiciones_historicas }}" {{ ($isOwnUser ? "" : "readonly") }}>{{ $usuarioDB->comentario_condiciones_historicas }}</textarea>
                    </div>
                </div>
            </fieldset>
        </div>

    @else

        <?php
            $consulta = "
                select 1
                from notifications
                where notifications.notifiable_id = :notifiable_id
                and notifiable_id is not null
                and notifiable_type = 'App\Usuario'
                and type = 'App\Notifications\AddListRequest'
                and (data::json->'doctor'->>'id') = :doctor_id
                order by created_at
                desc limit 1
            ";

            $resultado = DB::select($consulta, [
                "notifiable_id" => $usuarioDB->id,
                "doctor_id" => $usuario["id"],
            ]);

            $solicitud = count($resultado) === 0;
        ?>

        <div style="text-align: center; margin-top: 30px;">
            ¡Pídele a <span class="bold">{{ $usuarioDB->nombres }}</span> que te agregue a su lista de profesionales de salud para ver su <span class="underline">ficha médica</span>!
        </div>
        <div style="text-align: center; margin-top: 30px;">
            <button class="btn btn-primary btn-lg" id="enviar-solicitud-lista" {{ ($solicitud ? "" : "disabled") }}>
                {{ ($solicitud ? "Enviar solicitud" : "Solicitud enviada") }}
            </button>
        </div>

    @endif

@endsection

@section('scripts')
    <script type="text/javascript">

        $(function () {
            $('#ant-per-enf-act').select2({
                language: "es",
                placeholder: 'Busque y seleccione...'
            }).on('select2:select', function (e) {
                cambioCondicion($(this), 'actual', 'add', e);
            }).on('select2:unselect', function (e) {
                cambioCondicion($(this), 'actual', 'remove', e);
            });

            $('#ant-per-enf-hist').select2({
                language: "es",
                placeholder: 'Busque y seleccione...'
            }).on('select2:select', function (e) {
                cambioCondicion($(this), 'historica', 'add', e);
            }).on('select2:unselect', function (e) {
                cambioCondicion($(this), 'historica', 'remove', e);
            });

            $('#ant-per-act-etc').change(function () {
                cambioComentarioCondicion($(this), "actual", $(this).val());
            });

            $('#ant-per-hist-etc').change(function () {
                cambioComentarioCondicion($(this), "historica", $(this).val());
            });

            $('.ant-fam-list').on('change', 'input[id^="ant-fam-op-"]', function (e) {

                @if ($isOwnUser)
                    var $this = $(this),
                        checked = $this.is(':checked');

                    sendPost('{{ route('usuarios.ficha.saveActivacionAntFam') }}', {
                        _token: '{{ Session::token() }}',
                        id: $this.data('ant-fam'),
                        checked: checked ? 1 : 2
                    }, function (data) {
                        //todo - guardado exitoso (mini pop-up)

                        var nespecificaciones = $('input[id^="ant-fam-op-"]').filter(':checked').length;

                        if (nespecificaciones > 0) {
                            $('.ant-fam-esp').removeClass('hidden');
                        }
                        else {
                            $('.ant-fam-esp').addClass('hidden');
                        }

                        if (checked) {
                            $('.ant-fam-list-esp').append('<li class="ant-fam-esp-item" id="ant-fam-esp-item-' + $this.data('ant-fam') + '">' +
                                '<div class="form-group">' +
                                    '<label class="form-label" for="ant-fam-esp-txt-' + $this.data('ant-fam') + '">' + $this.next('label').text() +  '</label>' +
                                    '<input type="text" class="form-control" us-ant-fam="' + data.id + '" id="ant-fam-esp-txt-' + $this.data('ant-fam') + '">' +
                                '</div>' +
                            '</li>');
                        }
                        else {
                            $('#ant-fam-esp-item-' + $this.data('ant-fam')).remove();
                        }
                    }, function () {
                        $this.prop('checked', !checked);
                    });
                @else
                    e.preventDefault();
                @endif
            });


            $('.ant-fam-list-esp').on('change', 'input[id^="ant-fam-esp-txt-"]', function (e) {
                @if ($isOwnUser)
                    sendPost('{{ route('usuarios.ficha.saveEspecificacionAntFam') }}', {
                        _token: '{{ Session::token() }}',
                        id: $(this).attr('us-ant-fam'),
                        especificacion: $.trim($(this).val())
                    }, function () {
                        //todo - guardado exitoso (mini pop-up)
                    });
                @else
                    e.preventDefault();
                @endif
            });

            @if ($isOwnUser)
                $('#btn-add-integrante').click(function (e) {
                    e.preventDefault();

                    agregarEditarFamiliar("add");
                });

                $('.nf-action-edit').click(function () {
                    agregarEditarFamiliar("edit", $(this).closest('tr').data('datos'));
                });

                $('.nf-action-delete').click(function () {
                    removerFamiliar($(this).closest('tr').data('datos').id);
                });
            @endif

            $('#enviar-solicitud-lista').click(function () {
                sendPost('{{ route('usuario.sendaddlistrequest') }}', {
                    _token: '{{ csrf_token() }}',
                    id_paciente: '{{ $usuarioDB->id }}'
                }, function () {
                    mensajes.alerta("Solicitud enviada correctamente.", "Alerta", function () {
                        location.reload();
                    });
                });
            });
        });

        @if ($isOwnUser)
            function agregarEditarFamiliar(action, integrante) { //integrante: en caso de ser action === 'edit'

                $('<div class="">' +
                    '<div class="form-group" inp-name="parentesco">' +
                        '<label for="nf-parentesco" class="control-label">Parentesco</label>' +
                        '<select id="nf-parentesco" class="form-control">' +
                            '<option value="0">Seleccione</option>' +
                            @foreach ($parentescos as $p)
                                '<option value="{{ $p->id }}">{{ $p->nombre }}</option>' +
                            @endforeach
                        '</select>' +
                    '</div>' +
                    '<div class="form-group" inp-name="edad">' +
                        '<label for="nf-edad" class="control-label">Edad</label>' +
                        '<input id="nf-edad" type="number" class="form-control" min="0" max="120">' +
                    '</div>' +
                    '<div class="form-group" inp-name="estado_salud">' +
                        '<label for="nf-estado" class="control-label">Estado de salud</label>' +
                        '<select id="nf-estado" class="form-control">' +
                            '<option value="0">Seleccione</option>' +
                            @foreach ($estadosSalud as $est)
                                '<option value="{{ $est->id }}">{{ $est->nombre }}</option>' +
                            @endforeach
                        '</select>' +
                    '</div>' +
                    '<div class="form-group status-d hidden" inp-name="edad_muerte">' +
                        '<label for="nf-edad-m" class="control-label">Edad al morir</label>' +
                        '<input id="nf-edad-m" type="number" class="form-control" min="0" max="120">' +
                    '</div>' +
                    '<div class="form-group status-d hidden" inp-name="causa_muerte">' +
                        '<label for="nf-causa-m" class="control-label">Causa de muerte</label>' +
                        '<input id="nf-causa-m" type="text" class="form-control">' +
                    '</div>' +
                '</div>').dialog({
                    title: action === 'add' ? "Agregar integrante" : "Editar integrante",
                    width: 600,
                    modal: true,
                    autoOpen: true,
                    resizable: false,
                    closeOnEscape: false,
                    close: function () {
                        $(this).dialog('destroy').remove();
                    },
                    classes: { 'ui-dialog': 'dialog-responsive' },
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
                                sendPost('{{ route('usuarios.ficha.addEditIntegrante') }}', {
                                    _token: '{{ Session::token() }}',
                                    action: action,
                                    id: (action === 'edit' ? integrante.id : 0),
                                    parentesco: $.trim($('#nf-parentesco').val()),
                                    edad: $.trim($('#nf-edad').val()),
                                    estado_salud: $.trim($('#nf-estado').val()),
                                    edad_muerte: $.trim($('#nf-edad-m').val()),
                                    causa_muerte: $.trim($('#nf-causa-m').val())
                                }, function () {
                                    mensajes.alerta("Integrante " + (action === "add" ? "agregado" : "editado") + " correctamente.", "Guardado de datos", function () {
                                        location.reload();
                                    });
                                });
                            }
                        }
                    ]
                });

                $('#nf-estado').change(function () {
                    if ($(this).val() === "6") {
                        $('.status-d').removeClass('hidden');
                    }
                    else {
                        $('.status-d').addClass('hidden')
                            .find('input')
                            .val('');
                    }
                });

                if (action === "edit") {

                    $('#nf-parentesco').val(integrante.id_parentesco);
                    $('#nf-edad').val(integrante.edad);
                    $('#nf-estado').val(integrante.id_estado_salud);

                    if (integrante.id_estado_salud === 6) {
                        $('.status-d').removeClass('hidden');

                        $('#nf-edad-m').val(integrante.edad_muerte);
                        $('#nf-causa-m').val(integrante.causa_muerte);
                    }
                }
            }

            function removerFamiliar(id) {
                mensajes.confirmacion_sino("¿Está seguro de quitar a este familiar de la lista?", function () {
                    sendPost('{{ route('usuarios.ficha.removerIntegrante') }}', {
                        _token: '{{ Session::token() }}',
                        id: id
                    }, function () {
                        mensajes.alerta("Se ha quitado el integrante de la lista de núcleo familiar correctamente.", "Integrante removido", function () {
                            location.reload();
                        });
                    });
                });
            }

            function cambioCondicion($elem, tipo, accion, event) {

                var id = event.params.data.id;

                sendPost('{{ route('usuarios.ficha.cambioCondicion') }}', {
                    _token: '{{ Session::token() }}',
                    tipo: tipo,
                    accion: accion,
                    id: id
                }, function () {
                    //todo - guardado exitoso (mini pop-up)
                }, function () {
                    if (accion === "add") { //Hay que quitar elemento añadido
                        var newval = $elem.val().filter(function (elemento) {
                            return elemento !== id;
                        });

                        $elem.val(newval).change();
                    }
                    else if (accion === "remove") { //Hay que agregar elemento eliminado
                        var newvalr = $elem.val();

                        newvalr.push(id);

                        $elem.val(newvalr).change();
                    }
                });
            }

            function cambioComentarioCondicion($elem, tipo, txt) {
                sendPost('{{ route('usuarios.ficha.cambioCondicionComentario') }}', {
                    _token: '{{ Session::token() }}',
                    tipo: tipo,
                    texto: $.trim(txt)
                }, function () {
                    //todo - guardado exitoso (mini pop-up)

                    $elem.data('original', txt);
                }, function () {
                    $elem.val($elem.data('original'));
                });
            }
        @endif
    </script>
@endsection