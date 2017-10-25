<?php

use \App\Http\Controllers\UsuarioController;
use \App\Usuario;

$usuarioDB = Usuario::find($usuario["id"]);

$verificacion = $usuarioDB->verificaciones()->orderby('updated_at', 'desc')->first();

$solicitudVerificacion = $usuarioDB->solicitudes_verificacion()->orderby('updated_at', 'desc')->first();

$profesionalVerificado = ($usuario["autenticidad_profesional_verificada"] && $verificacion);

$titulo = $profesionalVerificado ? $verificacion->titulo_habilitante_legal : ($usuario["titulo_segun_usuario"] ? $usuario["titulo_segun_usuario"] : "Sin título especificado");
$institucion = $profesionalVerificado ? $verificacion->institucion_habilitante : ($usuario["institucion_habilitante_segun_usuario"] ? $usuario["institucion_habilitante_segun_usuario"] : "Sin institución especificada");
$especialidad = $profesionalVerificado ? $verificacion->especialidad : ($usuario["especialidad_segun_usuario"] ? $usuario["especialidad_segun_usuario"] : "Sin especialidad especificada");

//var_dump($profesionalVerificado);

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

        .content-title.title-profile-pic {
            margin-top: 30px;
            margin-left: 100px;
            margin-bottom: calc(10px + .15em);
            padding-left: 30px;
            position: relative;

            width: calc(100% - 100px);
        }

        .content-title.title-profile-pic img {
            position: absolute;
            left: -100px;
            top: -13px;

            width: 120px;
            height: 120px;
            padding: 5px;
            background-color: var(--secondary-background-color);

            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;

            -webkit-user-drag: none;

            -webkit-box-shadow: 0 0 10px 1px rgba(0,0,0,0.55);
            -moz-box-shadow: 0 0 10px 1px rgba(0,0,0,0.55);
            box-shadow: 0 0 10px 1px rgba(0,0,0,0.55);
        }

        .content-titulo-profesion {
            margin-left: 100px;
            padding-left: 30px;
            font-size: 1.35em;
            line-height: .85em;
        }

        .perfil-profesional-header {
            margin-bottom: 45px;
        }

        .control-label {
            text-align: left !important;
        }
    </style>
@endsection

@section('content')

    <div class="perfil-profesional-header">
        <div class="content-title title-profile-pic">
            <img src="{{ URL::to('profilePics/' . UsuarioController::getProfilePic($usuario["profile_pic_path"], $usuario["id_sexo"])) }}" class="img-circle">
            {{ $usuario["nombres"] . ' ' . $usuario["apellidos"] }}
        </div>
        <div class="content-titulo-profesion {{ $profesionalVerificado ? "verificado" : "no-verificado" }}">
            {{ $titulo }}
        </div>
    </div>
    <div class="basic-form-container form-horizontal">
        <div class="form-group" inp-name="institucion">
            <label for="txt-inst" class="col-sm-3 control-label">Institución habilitante</label>
            <div class="col-sm-9">
                <p class="form-control-static">{{ $institucion }}</p>
                <input type="text" id="txt-inst" class="form-control hidden" value="{{ $institucion }}" autocomplete="off">
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    <script type="text/javascript">

    </script>

@endsection