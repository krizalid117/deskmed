<?php

use \App\Http\Controllers\UsuarioController;
use \App\Usuario;

$usuarioDB = Usuario::find($usuario["id"]);

$verificacion = $usuarioDB->verificaciones()->orderby('updated_at', 'desc')->first();

$solicitudVerificacion = $usuarioDB->solicitudes_verificacion()->orderby('updated_at', 'desc')->first();

$profesionalVerificado = ($usuario["autenticidad_profesional_verificada"] && $verificacion);

$titulo = $profesionalVerificado ? $verificacion->titulo_habilitante_legal : (($usuario["titulo_segun_usuario"] && $usuario["titulo_segun_usuario"] !== "") ? $usuario["titulo_segun_usuario"] : "Sin especificar");
$institucion = $profesionalVerificado ? $verificacion->institucion_habilitante : (($usuario["institucion_habilitante_segun_usuario"] && $usuario["institucion_habilitante_segun_usuario"] !== "") ? $usuario["institucion_habilitante_segun_usuario"] : "Sin especificar");
$especialidad = $profesionalVerificado ? $verificacion->especialidad : (($usuario["especialidad_segun_usuario"] && $usuario["especialidad_segun_usuario"] !== "") ? $usuario["especialidad_segun_usuario"] : "Sin especificar");
$nregistro = $profesionalVerificado ? $verificacion->nregistro : (($usuario["nregistro_segun_usuario"] && $usuario["nregistro_segun_usuario"] !== "") ? $usuario["nregistro_segun_usuario"] : "Sin especificar");
$fechaRegistro = $profesionalVerificado ? $verificacion->fecha_registro : (($usuario["fecha_registro_segun_usuario"] && $usuario["fecha_registro_segun_usuario"] !== "") ? $usuario["fecha_registro_segun_usuario"] : "Sin especificar");
$antecedenteTitulo = $profesionalVerificado ? $verificacion->antecedente_titulo : (($usuario["antecedente_titulo_segun_usuario"] && $usuario["antecedente_titulo_segun_usuario"] !== "") ? $usuario["antecedente_titulo_segun_usuario"] : "Sin especificar");

//1 = verificado, 2 = verificacion en curso, 0 = no verificado
$estadoVerificacion = ($profesionalVerificado ? 1 : ($solicitudVerificacion && $solicitudVerificacion->estado === 0 ? 2 : 0));

$estadoIcono = "question"; //no verificado
$estadoTitle = "Profesional NO verificado";
$rgbEstado = "212, 89, 45";

switch ($estadoVerificacion) {
    case 1:
        $estadoIcono = "verified"; //verificado
        $estadoTitle = "Profesional verificado";
        $rgbEstado = "1, 210, 105";
        break;
    case 2:
        $estadoIcono = "waiting"; //Solicitud en proceso
        $estadoTitle = "Verificación en curso";
        $rgbEstado = "84, 94, 115";
        break;
}

?>

@extends('layouts.app')

@section('title', '| Perfil como Profesional de la salud')

@section('stylesheets')
    <style type="text/css">
        .verificado {
            color: #3b3b3b;
        }

        .no-verificado {
            color: #9d9d9d;
        }

        .is-verified {
            width: 40px;
            height: 40px;
            background-image: url('/img/{{ $estadoIcono }}.png');
            background-size: 40px 40px;

            position: absolute;
            right: 25px;
            bottom: 50%;
            transform: translateY(50%);
        }

        .titulo-body
        , .pp-estado-verificacion {
            display: none;
        }

        .pp-estado-verificacion {
            position: absolute;
            background-color: rgb({{ $rgbEstado }});
            width: 160px;
            color: white;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-weight: bold;
            padding: 10px 5px;

            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;

            bottom: calc(-30% - 20px);
            left: 50%;
            transform: translateX(-45%);
        }

        .pp-extra-option {
            text-align: right;
            padding-right: 15px;
        }

        @media (max-width: 767px) {
            .titulo-body {
                display: block;
            }

            .panel {
                margin-bottom: 10px !important;
            }

            .is-verified {
                right: -9px;
                bottom: 9px;
            }

            .pp-estado-verificacion {
                display: flex;
            }

            .professional-profile-header {
                margin-bottom: 70px;
            }
        }
    </style>
@endsection

@section('content')

    <div class="professional-profile-header">
        <div class="pp-pic-container">
            <img src="{{ URL::to('profilePics/' . UsuarioController::getProfilePic($usuario["profile_pic_path"], $usuario["id_sexo"])) }}" class="img-circle">
        </div>
        <div class="pp-presentation doctor">
            <div class="pp-fold doctor"></div>
            <div class="pp-name font-title-normal-on-xs" title="Nombre completo del profesional">
                {{ $usuario["nombres"] . ' ' . $usuario["apellidos"] }}
            </div>
            <div class="pp-title" title="Título o habilitación profesional">
                {{ $titulo }}
            </div>
            <span class="is-verified" title="{{ $estadoTitle }}"></span>
        </div>
        <div class="pp-estado-verificacion">
            {{ $estadoTitle }}
        </div>
    </div>
    <div class="basic-form-container">
        {{--<div style="text-align: right; padding: 0 15px; margin-bottom: 15px;">--}}
            {{--<button class="btn btn-primary">--}}
                {{--<span class="glyphicon glyphicon-pencil"></span>--}}
            {{--</button>--}}
        {{--</div>--}}
        <div class="col-sm-12 point titulo-body">
            <div class="panel panel-primary">
                <div class="panel-heading">Título o habilitación profesional</div>
                <div class="panel-body">
                    {{ $titulo }}
                </div>
            </div>
        </div>
        <div class="col-sm-6 point">
            <div class="panel panel-primary">
                <div class="panel-heading">Institución habilitante</div>
                <div class="panel-body">
                    {{ $institucion }}
                </div>
            </div>
        </div>
        <div class="col-sm-6 point">
            <div class="panel panel-primary">
                <div class="panel-heading">Especialidad</div>
                <div class="panel-body">
                    {{ $especialidad }}
                </div>
            </div>
        </div>
        <div class="col-sm-6 point">
            <div class="panel panel-primary">
                <div class="panel-heading">N° registro</div>
                <div class="panel-body">
                    {{ $nregistro }}
                </div>
            </div>
        </div>
        <div class="col-sm-6 point">
            <div class="panel panel-primary">
                <div class="panel-heading">Fecha registro</div>
                <div class="panel-body">
                    {{ $fechaRegistro }}
                </div>
            </div>
        </div>
        <div class="col-sm-12 point">
            <div class="panel panel-primary">
                <div class="panel-heading">Antecedente del Título o habilitación profesional</div>
                <div class="panel-body">
                    {{ $antecedenteTitulo }}
                </div>
            </div>
        </div>
        @if ($estadoVerificacion === 0)
        <div class="pp-extra-option">
            <a href="#" id="solicitar-verificacion">Solicitar verificación de título</a>
            <span class="ui-icon ui-icon-help deskmed-icon-help" title="Solicite que el equipo de Deskmed verifique la autenticidad de su título como profesional de la salud. Esto se hace consultando con la Superintendencia de Salud. Puede tardar 48 horas hábiles como máximo. Todos sus datos como profesional serán cargados automáticamente. Mientras, puede agregarlos por usted mismo, pero se le advertirá a cualquier otro usuario viendo su perfil profesional que aun no está verificado."></span>
        </div>
        @endif

        @if ($estadoVerificacion === 0 || $estadoVerificacion === 2)
            <div class="pp-extra-option">
                <a href="#" id="editar-pp-temporal">Agregar/editar datos temporales</a>
                <span class="ui-icon ui-icon-help deskmed-icon-help" title="Puede cambiar los datos de su perfil profesional de forma temporal mientras no esté verificado."></span>
            </div>
        @endif
    </div>
@endsection

@section('scripts')

    <script type="text/javascript">
        $(function () {
            $('#solicitar-verificacion').click(function (e) {
                e.preventDefault();

                $('<div>' +
                    'Al enviar esta solicitud el equipo de Deskmed validará su título profesional en el registro nacional de prestadores individuales de salud, en el sitio web de la Superintendencia de Salud, en un plazo máximo de 48 horas hábiles.<br><br>¿Desea enviar la solicitud?' +
                '</div>').dialog({
                    title: "Enviar solicitud de verificación",
                    width: 500,
                    classes: { 'ui-dialog': 'dialog-responsive' },
                    resizable: false,
                    modal: true,
                    autoOpen: true,
                    close: function () {
                        $(this).dialog('destroy').remove();
                    },
                    closeOnEscape: true,
                    buttons: [
                        {
                            text: "Cancelar",
                            'class': 'btn',
                            click: function () {
                                $(this).dialog('close');
                            }
                        },
                        {
                            text: "Enviar",
                            'class': 'btn btn-primary',
                            click: function () {
                                var $this = $(this);

                                sendPost('{{ route('usuario.profesion.verify') }}', {
                                    _token: '{{ csrf_token() }}'
                                }, function (res) {
                                    mensajes.alerta(res.mensaje, "Solicitud de verificación", function () {
                                        location.reload();
                                    });
                                });
                            }
                        }
                    ]
                });
            });

            $('#editar-pp-temporal').click(function (e) {
                e.preventDefault();

                $('<div>' +
                    '<div class="col-sm-12">' +
                        'La información proporcionada en este formulario será temporal hasta que verifique la validez de su título profesional.<hr>' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                        '<div class="form-group" inp-name="titulo">' +
                            '<label for="pp-temp-titulo" class="form-label">Título</label>' +
                            '<input type="text" class="form-control" id="pp-temp-titulo" autocomplete="off" value="{{ $usuario["titulo_segun_usuario"] }}">' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                        '<div class="form-group" inp-name="institucion">' +
                            '<label for="pp-temp-institucion" class="form-label">Intitución</label>' +
                            '<input type="text" class="form-control" id="pp-temp-institucion" autocomplete="off" value="{{ $usuario["institucion_habilitante_segun_usuario"] }}">' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                        '<div class="form-group" inp-name="especialidad">' +
                            '<label for="pp-temp-especialidad" class="form-label">Especialidad</label>' +
                            '<input type="text" class="form-control" id="pp-temp-especialidad" autocomplete="off" value="{{ $usuario["especialidad_segun_usuario"] }}">' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                        '<div class="form-group" inp-name="nregistro">' +
                            '<label for="pp-temp-nregistro" class="form-label">N° registro</label>' +
                            '<input type="text" class="form-control" id="pp-temp-nregistro" autocomplete="off" value="{{ $usuario["nregistro_segun_usuario"] }}">' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                        '<div class="form-group" inp-name="fregistro">' +
                            '<label for="pp-temp-fregistro" class="form-label">Fecha registro</label>' +
                            '<input type="text" class="form-control" id="pp-temp-fregistro" style="background-color: #fff; cursor: text;" autocomplete="off" readonly value="{{ $usuario["fecha_registro_segun_usuario"] }}">' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                        '<div class="form-group" inp-name="antecedente">' +
                            '<label for="pp-temp-antecedente" class="form-label">Antecedente de título</label>' +
                            '<input type="text" class="form-control" id="pp-temp-antecedente" autocomplete="off" value="{{ $usuario["antecedente_titulo_segun_usuario"] }}">' +
                        '</div>' +
                    '</div>' +
                '</div>').dialog({
                    title: "Agregar/editar perfil profesional de forma temporal",
                    width: 600,
                    classes: { 'ui-dialog': 'dialog-responsive' },
                    resizable: false,
                    modal: true,
                    autoOpen: true,
                    close: function () {
                        $(this).dialog('destroy').remove();
                    },
                    closeOnEscape: true,
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
                                sendPost('{{ route('usuario.profesion.savetemp') }}', {
                                    _token: '{{ csrf_token() }}',
                                    titulo: $.trim($('#pp-temp-titulo').val()),
                                    institucion: $.trim($('#pp-temp-institucion').val()),
                                    especialidad: $.trim($('#pp-temp-especialidad').val()),
                                    nregistro: $.trim($('#pp-temp-nregistro').val()),
                                    fregistro: $.trim($('#pp-temp-fregistro').val()),
                                    antecedente: $.trim($('#pp-temp-antecedente').val())
                                }, function () {
                                    mensajes.alerta("Datos guardados correctamente", "Guardado de datos", function () {
                                        location.reload();
                                    });
                                });
                            }
                        }
                    ]
                });

                $('#pp-temp-fregistro').datepicker({
                    maxDate: "{{ date('d-m-Y') }}"
                });
            });
        });
    </script>

@endsection