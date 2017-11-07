<?php

use \App\Http\Controllers\UsuarioController;
use \App\Http\Controllers\GlobalController;

$verDoctores = $usuario->id_tipo_usuario === 3 || $usuario->id_tipo_usuario === 1;
$verPacientes = false; //$usuario->id_tipo_usuario === 2 || $usuario->id_tipo_usuario === 1;

?>

@extends('layouts.app')

@section('title', '| Búsqueda: ' . $keyword)

@section('stylesheets')
    <style type="text/css">
        .no-results {
            text-align: center;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .search-results-list {
            list-style: none;
        }

        .search-results-list > li > a {
            display: flex;
            align-items: center;
            text-decoration: none;

            padding: 15px;

            border-bottom: 1px solid #e5e5e5;

            -webkit-user-drag: none;
        }

        .search-results-list > li > a:hover {
            background-color: #23527c;
            color: #fff !important;
        }

        .search-results-list > li > a:active
        , .search-results-list > li > a:hover
        , .search-results-list > li > a:visited {
            text-decoration: none;

            color: #23527c;
        }

        .result-profile-pic {
            position: relative;
        }

        .result-profile-pic img.profile-pic {
            width: 80px;
            height: 80px;

            -webkit-user-drag: none;
        }

        .result-profile-pic img.estado {
            width: 25px;
            height: 25px;

            position: absolute;
            right: -3px;
            top: 0;

            -webkit-user-drag: none;
        }

        .result-name {
            padding-left: 15px;
        }

        fieldset {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
    </style>
@endsection

@section('content')
    @if (($results["d"]["count"] > 0 && $verDoctores) || ($results["p"]["count"] > 0 && $verPacientes))
        <div class="basic-form-container">
            Mostrando resultados para la búsqueda: <span class="bold">"{{ $keyword }}"</span>

            @if($results["d"]["count"] > 0 && $verDoctores)
                <fieldset class="fs-cllapsable" data-collapsed="false">
                    <legend class="fs-collapsable-title"><span class="ui-icon ui-icon-minus"></span>Profesionales de la salud ({{ $results["d"]["count"] }})</legend>
                    <div class="fs-collapsable-content">
                        <ul class="search-results-list search-docs">
                            @foreach ($results["d"]["results"] as $r)
                                <li class="search-result-item-d">
                                    <a href="{{ route('doctors.profile', $r->id) }}" class="result-item">
                                        <div class="result-profile-pic">
                                            <img class="profile-pic img-circle" src="{{ '/profilePics/' . UsuarioController::getProfilePic($r->profile_pic_path, $r->id_sexo) }}" alt="{{ $r->nombres }}">
{{--                                            <img class="profile-pic img-circle" src="{{ "http://graph.facebook.com/v2.5/" . random_int(555, 10000) . "/picture?width=400&height=400" }}" alt="{{ $r->nombres }}">--}}
                                            <img class="estado" src="/img/{{ $r->icon }}.png" alt="{{ $r->icon }}" title="{{ ($r->icon === "verified" ? "Profesional de la salud verificado" : ($r->icon === "waiting" ? "Verificación profesional en proceso" : "Profesional sin verificación")) }}">
                                        </div>
                                        <div class="result-name">
                                            <span class="bold">{{ $r->nombres . " " . $r->apellidos }}</span>
                                            <br>
                                            Título: <span class="text-muted">{{ $r->titulo }}</span>. Especialidad: <span class="text-muted">{{ $r->especialidad }}</span>.
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </fieldset>
            @endif

            @if($results["p"]["count"] > 0 && $verPacientes)
                <fieldset class="fs-cllapsable" data-collapsed="false">
                    <legend class="fs-collapsable-title"><span class="ui-icon ui-icon-minus"></span>Pacientes ({{ $results["p"]["count"] }})</legend>
                    <div class="fs-collapsable-content">
                        <ul class="search-results-list search-pat">
                            @foreach ($results["p"]["results"] as $r)
                                <li class="search-result-item-p">
                                    <a href="{{ route('patients.profile', $r->id) }}" class="result-item">
                                        <div class="result-profile-pic">
                                            <img class="profile-pic img-circle" src="{{ '/profilePics/' . UsuarioController::getProfilePic($r->profile_pic_path, $r->id_sexo) }}" alt="{{ $r->nombres }}">
{{--                                            <img class="profile-pic img-circle" src="{{ "http://graph.facebook.com/v2.5/" . random_int(1, 10000) . "/picture?width=400&height=400" }}" alt="{{ $r->nombres }}">--}}
                                        </div>
                                        <div class="result-name">
                                            <span class="bold">{{ $r->nombres . " " . $r->apellidos }}</span>
                                            <br>
                                            <span class="text-muted">{{ (GlobalController::edad_anios($r->fecha_nacimiento) > 17 ? $r->alias_adulto : $r->alias_infantil) }}. {{ GlobalController::edad($r->fecha_nacimiento) }}</span>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </fieldset>
            @endif
        </div>
    @else
        <div class="no-results">
            <span>No se encontraron resultados para la búsqueda <span class="bold">"{{ $keyword }}"</span>.</span>
        </div>
    @endif
@endsection

@section('scripts')
    <script type="text/javascript">
        $(function () {
            $('.txt-header-search').focus().select();
        });
    </script>
@endsection