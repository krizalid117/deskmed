@extends('layouts.app')

@section('title', '| Perfil')

@section('stylesheets')
    <style type="text/css">
        .control-label {
            text-align: left !important;
        }
    </style>
@endsection

@section('content')

<?php
    use App\Sexos;
    use \App\Http\Controllers\UsuarioController;

    $usuario = Auth::user()["attributes"];

    $sexos = Sexos::pluck('nombre', 'id');
?>

    <div class="content-title">
        Configuraciones de perfil
    </div>

    <div class="basic-form-container">
        <button class="btn btn-primary btn-profile-allow-edit" title="Habilitar edición de datos">
            <span class="glyphicon glyphicon-pencil"></span>
        </button>
        <div class="profile-img-container">
            <img class="img-circle" src="{{ URL::to('profilePics/' . UsuarioController::getProfilePic($usuario["profile_pic_path"], $usuario["id_sexo"])) }}" alt="Foto de perfil">
            <div class="profile-img-options hidden">
                <span class="glyphicon glyphicon-edit" title="Cambiar foto de perfil"></span>
                <span class="glyphicon glyphicon-trash" title="Eliminar foto de perfil"></span>
            </div>
        </div>
    </div>

    <div class="basic-form-container form-horizontal">
        <div class="form-group">
            <label for="txt-profile-email" class="col-sm-3 control-label">Correo electrónico</label>
            <div class="col-sm-9">
                <input type="email" id="txt-profile-email" class="form-control" value="{{ $usuario["email"] }}" disabled>
            </div>
        </div>
        <div class="form-group">
            <label for="txt-profile-nombres" class="col-sm-3 control-label">Nombres</label>
            <div class="col-sm-9">
                <input type="text" id="txt-profile-nombres" class="form-control" value="{{ $usuario["nombres"] }}" disabled>
            </div>
        </div>
        <div class="form-group">
            <label for="txt-profile-apellidos" class="col-sm-3 control-label">Apellidos</label>
            <div class="col-sm-9">
                <input type="text" id="txt-profile-apellidos" class="form-control" value="{{ $usuario["apellidos"] }}" disabled>
            </div>
        </div>
        <div class="form-group">
            <label for="txt-profile-fnac" class="col-sm-3 control-label">Fecha de nacimiento</label>
            <div class="col-sm-9 date-container">
                <input type="text" id="txt-profile-fnac" class="form-control" value="{{ $usuario["fecha_nacimiento"] }}" disabled readonly>
                <span class="glyphicon glyphicon-calendar"></span>
            </div>
        </div>
        <div class="form-group">
            <label for="txt-profile-sexo" class="col-sm-3 control-label">Sexo</label>
            <div class="col-sm-9">
                <select id="txt-profile-sexo" class="form-control" disabled>
                    @foreach($sexos as $id => $sexo)
                        <option value="{{ $id }}" {{ ($id === $usuario["id_sexo"] ? "selected" : "") }}>{{ $sexo }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(function () {
            $('#txt-profile-fnac').datepicker();
        });
    </script>
@endsection