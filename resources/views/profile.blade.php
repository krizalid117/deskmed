@extends('layouts.app')

@section('title', '| Perfil')

@section('stylesheets')
    <style type="text/css">
        .control-label {
            text-align: left !important;
        }

        #txt-profile-fnac:not(:disabled) {
            background-color: #fff !important;
            cursor: text;
        }

        .dropdown-menu {
            margin: 0 !important;
            min-width: 100px !important;
        }

        .dropdown-menu > li > a {
            padding: 3px 15px !important;
        }

        .dropdown-menu > li > a >span.glyphicon {
            margin-right: 10px;
        }

        .ui-icon-check {
            display: inline-block;
        }

        .priv-nombre {
            width: 70px;
            display: inline-block;
        }

        .edit-buttons-container {
            text-align: center;
            margin-bottom: 15px;
            display: none;
        }

        #btn-edit-cancel {
            margin-right: 10px;
        }
    </style>
@endsection

@section('content')

<?php
        use \App\Http\Controllers\UsuarioController;

?>

    <div class="content-title">
        Configuraciones de perfil
    </div>

    <div class="basic-form-container">
        <button class="btn btn-primary btn-profile-allow-edit" title="Habilitar edición de datos">
            <span class="glyphicon glyphicon-pencil"></span>
        </button>
        <div class="profile-img-container">
            <img class="img-circle" src="{{ URL::to('profilePics/' . $profilePic) }}" alt="Foto de perfil">
            <div class="profile-img-options hidden">
                <span class="glyphicon glyphicon-edit" title="Cambiar foto de perfil"></span>
                <span class="glyphicon glyphicon-trash" title="Eliminar foto de perfil"></span>
            </div>
        </div>
    </div>

    <div class="basic-form-container form-horizontal">
        <div class="form-group">
            <label for="txt-profile-id" class="col-sm-3 control-label">Identificador ({{ $tipoIdentificador }})</label>
            <div class="col-sm-9">
                <div class="input-group">
                    <input type="text" id="txt-profile-id" class="form-control" value="{{ ($usuario["id_tipo_identificador"] === 1 ? UsuarioController::upRut($usuario["identificador"]) : $usuario["identificador"]) }}" disabled>
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-default dropdown-toggle btn-id-priv" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Opciones de privacidad de tu identificador" disabled>
                            <span class="{{ UsuarioController::getPrivacyIconClass($usuario["id_privacidad_identificador"]) }}"></span>
                            <span class="caret hidden"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            @foreach($opcionesPrivacidad as $op)
                                <li>
                                    <a href="#" class="privacy-op" data-id-privacy="{{ $op->id }}" data-glyph="{{ UsuarioController::getPrivacyIconClass($op->id) }}">
                                        <span class="{{ UsuarioController::getPrivacyIconClass($op->id) }}"></span>
                                        <span class="priv-nombre">{{ $op->nombre }}</span>
                                        {!! ($op->id === $usuario["id_privacidad_identificador"] ? '<span class="ui-icon ui-icon-check"></span>' : "") !!}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
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
            <label for="txt-profile-email" class="col-sm-3 control-label">Correo electrónico</label>
            <div class="col-sm-9">
                <input type="email" id="txt-profile-email" class="form-control" value="{{ $usuario["email"] }}" disabled>
            </div>
        </div>
        <div class="form-group">
            <label for="txt-profile-fnac" class="col-sm-3 control-label">Fecha de nacimiento</label>
            <div class="col-sm-9 date-container">
                <input type="text" id="txt-profile-fnac" class="form-control" value="{{ date('d-m-Y', strtotime($usuario["fecha_nacimiento"])) }}" disabled readonly>
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
    <div class="edit-buttons-container">
        <button class="btn" id="btn-edit-cancel">Cancelar</button>
        <button class="btn btn-primary" id="btn-edit-ok">Guardar cambios</button>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        var btnAllowEdit = $('.btn-profile-allow-edit');
        var btnIdPriv = $('.btn-id-priv');

        btnAllowEdit.data('active', false);
        btnIdPriv.data('selected', '{{ $usuario["id_privacidad_identificador"] }}');

        $(function () {
            $('#txt-profile-fnac').datepicker({
                maxDate: "{{ date('d-m-Y') }}"
            });

            btnAllowEdit.click(function () {
                allowEdit();
            });

            $('.privacy-op').click(function (e) {
                e.preventDefault();

                var $this = $(this);

                btnIdPriv.data('selected', $this.data('id-privacy'));

                var span = btnIdPriv.find('span.glyphicon');

                span.removeClass('glyphicon');
                span.removeClass('glyphicon-eye-open');
                span.removeClass('glyphicon-user');
                span.removeClass('glyphicon-ban-circle');

                span.addClass($this.data('glyph'));

                $this.closest('ul').find('.ui-icon-check').remove();
                $this.append('<span class="ui-icon ui-icon-check">');
            });

            $('#btn-edit-cancel').click(function () {
                cancelEdit();
            });

            $('#btn-edit-ok').click(function () {
                guardarCambios();
            });
        });

        function allowEdit() {
            btnAllowEdit.data('active', true);

            $('#txt-profile-id').next('.input-group-btn').children('button').prop('disabled', false).find('.caret').removeClass('hidden');
            $('#txt-profile-nombres').prop('disabled', false);
            $('#txt-profile-apellidos').prop('disabled', false);
            $('#txt-profile-email').prop('disabled', false);
            $('#txt-profile-fnac').prop('disabled', false);
            $('#txt-profile-sexo').prop('disabled', false);

            btnAllowEdit.css('visibility', 'hidden');
            $('.edit-buttons-container').show();
        }

        function cancelEdit() {
            location.reload();
        }

        function guardarCambios() {
            var datos = {
                id_privacy: btnIdPriv.data('selected'),
                nombres: $('#txt-profile-nombres').val(),
                apellidos: $('#txt-profile-apellidos').val(),
                email: $('#txt-profile-email').val(),
                fecha_nacimiento: $('#txt-profile-fnac').val(),
                sexo: $('#txt-profile-sexo').val(),
                _token: '{{ csrf_token() }}'
            };

            sendPost('{{ route('usuario.edit') }}', datos, function (respuesta) {

            });
        }
    </script>
@endsection