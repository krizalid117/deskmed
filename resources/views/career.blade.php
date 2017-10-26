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
$nregistro = $profesionalVerificado ? $verificacion->nregistro : (($usuario["nregistro_segun_usuario"] && $usuario["nregistro_segun_usuario"] !== "") ? $usuario["nregistro_segun_usuario"] : "Sin espeficicar");
$fechaRegistro = $profesionalVerificado ? $verificacion->fecha_registro : (($usuario["fecha_registro_segun_usuario"] && $usuario["fecha_registro_segun_usuario"] !== "") ? $usuario["fecha_registro_segun_usuario"] : "Sin espeficicar");
$antecedenteTitulo = $profesionalVerificado ? $verificacion->antecedente_titulo : (($usuario["antecedente_titulo_segun_usuario"] && $usuario["antecedente_titulo_segun_usuario"] !== "") ? $usuario["antecedente_titulo_segun_usuario"] : "Sin espeficicar");

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

        .professional-profile-header {
            width: 100%;
            padding: 5px 25px;
            margin-top: 15px;
            margin-bottom: 15px;

            position: relative;
            display: flex;
            align-items: center;
        }

        .pp-pic-container img {
            width: 130px;
            height: 130px;
            padding: 5px;
            background-color: var(--secondary-background-color);

            position: absolute;
            top: 3px;
            left: 20px;

            z-index: 1;

            flex: none;

            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;

            -webkit-user-drag: none;

            -webkit-box-shadow: 0 0 10px 1px rgba(0,0,0,0.55);
            -moz-box-shadow: 0 0 10px 1px rgba(0,0,0,0.55);
            box-shadow: 0 0 10px 1px rgba(0,0,0,0.55);
        }

        .pp-presentation {
            width: 100%;
            height: 120px;
            margin-left: 60px;
            /*padding-left: 65px;*/

            position: relative;
            z-index: 0;

            display: flex;
            align-items: center;
            justify-content: center;
            flex-flow: column;
            flex: auto;

            text-align: center;

            background-color: #337ab7;
            color: #fff;

            border-bottom-right-radius: 15px;

            -webkit-box-shadow: -2px 3px 5px 1px rgba(0,0,0,0.55);
            -moz-box-shadow: -2px 3px 5px 1px rgba(0,0,0,0.55);
            box-shadow: -2px 3px 5px 1px rgba(0,0,0,0.55);
        }

        .pp-presentation > div {
            padding-right: 5px;
        }

        .pp-fold {
            background-color: #286090;
            position: absolute;
            top: -10px;
            right: 0;

            width: 15px;
            height: 10px;
            border-top-right-radius: 100%;
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

        @media (max-width: 767px) {
            .titulo-body {
                display: block;
            }

            .pp-presentation {
                font-size: .75em;
                padding-left: 65px;
                padding-right: 5px;
            }

            .pp-title {
                display: none;
            }

            .pp-presentation > div {
                padding-right: 5px;
            }

            .panel {
                margin-bottom: 10px !important;
            }

            .is-verified {
                right: -9px;
                bottom: 9px;
            }

            .pp-presentation
            , .pp-pic-container img {
                box-shadow: none;
            }

            .professional-profile-header {
                margin-bottom: 70px;
            }

            .pp-estado-verificacion {
                display: flex;
            }
        }
    </style>
@endsection

@section('content')

    <div class="professional-profile-header">
        <div class="pp-pic-container">
            <img src="{{ URL::to('profilePics/' . UsuarioController::getProfilePic($usuario["profile_pic_path"], $usuario["id_sexo"])) }}" class="img-circle">
        </div>
        <div class="pp-presentation">
            <div class="pp-fold"></div>
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
        <div style="text-align: right; padding-right: 15px;">
            <a href="#" id="solicitar-verificacion">Solicitar verificación de título</a>
            <span class="ui-icon ui-icon-help deskmed-icon-help" title="Solicite que el equipo de Deskmed verifique la autenticidad de su título como profesional de la salud. Esto se hace consultando con la Superintendencia de Salud. Puede tardar 48 horas hábiles como máximo. Todos sus datos como profesional serán cargados automáticamente. Mientras, puede agregarlos por usted mismo, pero se le advertirá a cualquier otro usuario viendo su perfil profesional que aun no está verificado."></span>
        </div>
        @endif
    </div>
@endsection

@section('scripts')

    <script type="text/javascript">
        $('#solicitar-verificacion').click(function (e) {
            e.preventDefault();


        });
    </script>

@endsection