<?php

use \App\Http\Controllers\UsuarioController;
use \App\Http\Controllers\GlobalController;
use \App\Usuario;
use \App\AntecedentesFamiliaresOpciones;
use Illuminate\Support\Facades\DB;

$anios = GlobalController::edad_anios($usuario["fecha_nacimiento"]);
$sexo =  Usuario::find($usuario["id"])->sexo()->first();
$usuarioAntFam =  Usuario::find($usuario["id"])->antecedentesFamiliares()->get()->toArray();

$usuarioAntFamId = []; //id de antecedentes familiares ya seteados por el usuario

foreach ($usuarioAntFam as $afo) {
    $usuarioAntFamId[] = $afo["id_antecedentes_familiares_opciones"];
}

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
            flex: 0 0 200px;
            margin-bottom: 5px;

            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .ant-fam-list > li > div {
            /*display: flex;*/
            /*justify-content: flex-start;*/
            /*align-items: center;*/
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

        .pretty i {
            margin-top: 3px;
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
                <div class="ficha-header">Indique las enfermedades que haya padecido <span class="bold">algún familiar cercano</span>:</div>
                {{--<select class="form-control" id="enf-fam" style="width: 100% !important;" multiple="multiple">--}}
                    {{--@foreach ($ant_fam_op as $afo)--}}
                        {{--<option value="{{ $afo->id }}">{{ $afo->nombre }}</option>--}}
                    {{--@endforeach--}}
                {{--</select>--}}
                <ul class="ant-fam-list">
                    @foreach ($ant_fam_op as $afo)
                        <li>
                            <div class="pretty o-danger curvy">
                                <input type="checkbox" data-ant-fam="{{ $afo->id }}" id="ant-fam-op-{{ $afo->id }}" data-especifica="{{ ($afo->necesita_especificacion ? "true" : "false") }}" {{ (in_array($afo->id, $usuarioAntFamId) !== false ? "checked" : "") }}>
                                <label for="ant-fam-op-{{ $afo->id }}" class="bold"><i class="glyphicon glyphicon-ok"></i> {{ $afo->nombre }} {{ ($afo->necesita_especificacion ? "(e)" : "") }}</label>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <div class="ant-fam-esp panel panel-danger">
                    <div class="panel-heading">Especificaciones <span class="ui-icon ui-icon-help deskmed-icon-help" title="asdasd"></span></div>
                    <div class="panel-body">
                        <ul class="ant-fam-list ant-fam-list-esp">

                        </ul>
                    </div>
                </div>
            </div>
        </fieldset>
        <fieldset class="fs-cllapsable" data-collapsed="false">
            <legend class="fs-collapsable-title"><span class="ui-icon ui-icon-minus"></span>Núcleo familiar</legend>
            <div class="fs-collapsable-content">
                asd2
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

            $('.ant-fam-list').on('change', 'input[id^="ant-fam-op-"]', function () {
                var $this = $(this),
                    checked = $this.is(':checked');

                sendPost('{{ route('usuarios.ficha.saveActivacionAntFam') }}', {
                    _token: '{{ csrf_token() }}',
                    id: $this.data('ant-fam'),
                    checked: checked ? 1 : 2
                }, function () {
                    //todo - guardado exitoso (mini pop-up)

                    if ($this.data('especifica')) {
                        if (checked) {
                            $('.ant-fam-list-esp').append('<li class="ant-fam-esp-item" id="' + $this.data('ant-fam') + '">' +
                                '<div class="form-group">' +
                                    '<label class="form-label">' + $this.next('label').text() +  '</label>' +
                                    '<input type="text" class="form-control" id="ant-fam-esp-txt-' + $this.data('ant-fam') + '">' +
                                '</div>' +
                            '</li>');
                        }
                        else {
                            $('li.ant-fam-esp-item[id="' + $this.data('ant-fam') + '"]').remove();
                        }
                    }
                });
            });
        });

    </script>

@endsection