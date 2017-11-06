<?php
    use \App\Http\Controllers\UsuarioController;
?>

@extends('layouts.app')

@section('title', '| Mis profesionales de salud')

@section('stylesheets')
    <style>
        .doc-list {
            list-style: none;
        }

        .doc-list-item {
            display: flex;
            align-items: center;
            justify-content: flex-start;

            margin-bottom: 15px;
            /*margin-right: 5px;*/
            /*margin-left: 5px;*/
            padding-right: 30px;
            padding-top: 10px;
            padding-bottom: 10px;
            position: relative;
            background-color: #e6e5e5;
            border-right: 5px solid var(--normal-background-color);
            border-left: 5px solid var(--normal-background-color);
        }

        .doc-list-item img.profile-pic {
            width: 80px;
            height: 80px;

            -webkit-user-drag: none;
        }

        .doc-list-item img.estado {
            width: 25px;
            height: 25px;

            position: absolute;
            left: 75px;
            top: 10px;

            -webkit-user-drag: none;
        }

        .doc-list-item .glyphicon-option-vertical {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
        }

        .doc-info {
            padding-left: 10px;
        }

        .docs-filters-container > div {
            padding-left: 0 !important;
            padding-right: 0 !important;

            min-height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 767px) {
            .docs-filters-container > div {
                justify-content: flex-start;
            }
        }
    </style>
@endsection

@section('content')

    <fieldset class="fs-collapsable" data-collapsed="false">
        <legend class="fs-collapsable-title" style="margin-bottom: 10px;">
            <span class="ui-icon ui-icon-minus"></span>
            Filtros
        </legend>
        <div class="fs-collapsable-content" style="padding: 0 5px;">
            <div class="docs-filters-container">
                <div class="col-sm-4">
                    <div class="form-group" style="margin-bottom: 0; width: 90%;">
                        <input type="text" placeholder="Filtrar..." class="form-control">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="pretty curvy" style="margin: 0;">
                        <input type="checkbox" id="chk-filter-verified">
                        <label for="chk-filter-verified" class="bold"><i class="glyphicon glyphicon-ok"></i> Profesionales verificados</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="pretty curvy" style="margin: 0;">
                        <input type="checkbox" id="chk-filter-tratamiento">
                        <label for="chk-filter-tratamiento" class="bold"><i class="glyphicon glyphicon-ok"></i> Tiene tratamientos conmigo</label>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>

    <div class="basic-form-container">

        @if(count($doctores) > 0)
            <div style="padding-left: 5px; margin-bottom: 15px;">
                Mostrando <span class="bold doc-count">{{ count($doctores) }}</span> profesionales:
            </div>

            <ul class="doc-list">
                @foreach($doctores as $doctor)
                    <?php
                        $doc = $doctor->doctor()->first();
                        $verificacion = $doc->verificaciones()->first();
                        $profesionalVerificado = (!is_null($verificacion) && $verificacion->habilitado);
                        $solicitudVerificacion = $doc->solicitudes_verificacion()->orderby('updated_at', 'desc')->first();

                        $titulo = ($profesionalVerificado ? $verificacion->titulo_habilitante_legal : (($doc->titulo_segun_usuario !== "" && !is_null($doc->titulo_segun_usuario)) ? $doc->titulo_segun_usuario : "Ninguno"));
                        $especialidad = ($profesionalVerificado ? $verificacion->especialidad : (($doc->especialidad_segun_usuario !== "" && !is_null($doc->especialidad_segun_usuario)) ? $doc->especialidad_segun_usuario : "Ninguna"));
                        $icon = ($profesionalVerificado ? "verified" : ($solicitudVerificacion && $solicitudVerificacion->estado === 0 ? "waiting" : "question"));
                    ?>
                    <li class="doc-list-item col-sm-6">
                        <img class="profile-pic img-circle" src="/profilePics/{{ UsuarioController::getProfilePic($doc->profile_pic_path, $doc->id_sexo) }}">
                        <img class="estado" src="/img/{{ $icon }}.png" alt="{{ $icon }}" title="{{ ($icon === "verified" ? "Profesional de la salud verificado" : ($icon === "waiting" ? "Verificación profesional en proceso" : "Profesional sin verificación")) }}">
                        <div class="doc-info">
                            <span class="bold">{{ $doc->nombres . " " . $doc->apellidos }}</span>
                            <br>
                            <span>
                                Título: <span class="text-muted">{{ $titulo }}</span>. Especialidad: <span class="text-muted">{{ $especialidad }}</span>.
                            </span>
                        </div>
                        <span class="glyphicon glyphicon-option-vertical"></span>
                    </li>
                @endforeach
            </ul>
        @else
            No tienes doctores agregados a tu lista
        @endif
    </div>

@endsection

@section('scripts')
    <script type="text/javascript">

    </script>
@endsection