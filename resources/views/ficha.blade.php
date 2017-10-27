<?php

use \App\Http\Controllers\UsuarioController;
use \App\Http\Controllers\GlobalController;
use \App\Usuario;

$anios = GlobalController::edad_anios($usuario["fecha_nacimiento"]);
$sexo =  Usuario::find($usuario["id"])->sexo()->first();

?>

@extends('layouts.app')

@section('title', '| Ficha de salud')

@section('stylesheets')
    <style type="text/css">
        .ficha-header {
            margin-bottom: 5px;
        }
    </style>
@endsection

@section('content')

    <div class="professional-profile-header">
        <div class="pp-pic-container">
            <img src="{{ URL::to('profilePics/' . UsuarioController::getProfilePic($usuario["profile_pic_path"], $usuario["id_sexo"])) }}" class="img-circle">
        </div>
        <div class="pp-presentation paciente">
            <div class="pp-fold paciente"></div>
            <div class="pp-name font-title-normal-on-xs" title="Nombre completo del profesional">
                {{ $usuario["nombres"] . ' ' . $usuario["apellidos"] }}
            </div>
            <div class="pp-title" title="Sexo, edad.">
                {{ $anios > 17 ? $sexo->alias_adulto : $sexo->alias_infantil }}. {{ GlobalController::edad($usuario["fecha_nacimiento"]) }}
            </div>
        </div>
    </div>

    <div class="basic-form-container">
        <fieldset class="fs-cllapsable" data-collapsed="false">
            <legend class="fs-collapsable-title"><span class="ui-icon ui-icon-minus"></span>Antecedentes familiares</legend>
            <div class="fs-collapsable-content">
                <div class="ficha-header">Indique las enfermedades que haya padecido algún familiar cercano:</div>
                <select class="form-control" id="enf-fam" style="width: 100% !important;" multiple="multiple">
                    <option value="1">a</option>
                    <option value="2">b</option>
                    <option value="3">c</option>
                </select>
            </div>
        </fieldset>
        <fieldset class="fs-cllapsable" data-collapsed="false">
            <legend class="fs-collapsable-title"><span class="ui-icon ui-icon-minus"></span>Antecedentes personales</legend>
            <div class="fs-collapsable-content">
                asd2
            </div>
        </fieldset>
    </div>

@endsection

@section('scripts')

    <script type="text/javascript">

        $(function () {
            $('#enf-fam').select2({
                language: "es"
            });
        });

    </script>

@endsection