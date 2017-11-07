<?php

use \App\Http\Controllers\UsuarioController;

?>

@extends('layouts.app')

@section('title', '| Cuenta')

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

        .profile-img-options {
            position: absolute;
            bottom: 5px;
            right: 0;
        }

        .profile-img-options span {
            cursor: pointer;
            opacity: .5;
        }

        .profile-img-options span:hover {
            opacity: 1;
        }

        .btn-profile-allow-edit {
            /*position: absolute;*/
            /*top: 0;*/
            /*right: 15px;*/
        }

        .btn-profile-change-pw {
            position: absolute;
            top: 40px;
            right: 15px;
        }

        .password-fields, .change-pass-container {
            display: none;
        }

        .profile-pic-upload {
            cursor: pointer;
            width: 100%;
            overflow: hidden;
        }
    </style>
@endsection

@section('content')

    <div class="content-title">
        Cuenta y datos personales
    </div>

    <div class="basic-form-container">
        <div class="profile-img-container">
            <img class="img-circle" src="{{ URL::to('profilePics/' . $profilePic) }}" alt="Foto de perfil">
            <div class="profile-img-options">
                <span class="glyphicon glyphicon-edit" title="Cambiar foto de perfil" id="profile-pic-edit"></span>
                <span class="glyphicon glyphicon-trash" title="Eliminar foto de perfil" id="profile-pic-remove"></span>
            </div>
        </div>
    </div>

    <div class="basic-form-container form-horizontal">
        <div style="text-align: right; margin-bottom: 15px;">
            <button class="btn btn-primary btn-profile-allow-edit" title="Habilitar edición de datos">
                <span class="glyphicon glyphicon-pencil"></span>
            </button>
        </div>
        <div class="form-group" inp-name="id_privacy">
            <label for="txt-profile-id" class="col-sm-3 control-label">Identificador ({{ $tipoIdentificador }})</label>
            <div class="col-sm-9">
                <div class="input-group">
                    <input type="text" id="txt-profile-id" class="form-control" value="{{ ($usuario->id_tipo_identificador === 1 ? UsuarioController::upRut($usuario->identificador) : $usuario->identificador) }}" disabled autocomplete="off">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-default dropdown-toggle btn-id-priv" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Opciones de privacidad de tu identificador" disabled>
                            <span class="{{ UsuarioController::getPrivacyIconClass($usuario->id_privacidad_identificador) }}"></span>
                            <span class="caret hidden"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            @foreach($opcionesPrivacidad as $op)
                                <li>
                                    <a href="#" class="privacy-op" data-id-privacy="{{ $op->id }}" data-glyph="{{ UsuarioController::getPrivacyIconClass($op->id) }}">
                                        <span class="{{ UsuarioController::getPrivacyIconClass($op->id) }}"></span>
                                        <span class="priv-nombre">{{ $op->nombre }}</span>
                                        {!! ($op->id === $usuario->id_privacidad_identificador ? '<span class="ui-icon ui-icon-check"></span>' : "") !!}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group" inp-name="nombres">
            <label for="txt-profile-nombres" class="col-sm-3 control-label">Nombres</label>
            <div class="col-sm-9">
                <input type="text" id="txt-profile-nombres" class="form-control" value="{{ $usuario->nombres }}" disabled autocomplete="off">
            </div>
        </div>
        <div class="form-group" inp-name="apellidos">
            <label for="txt-profile-apellidos" class="col-sm-3 control-label">Apellidos</label>
            <div class="col-sm-9">
                <input type="text" id="txt-profile-apellidos" class="form-control" value="{{ $usuario->apellidos }}" disabled autocomplete="off">
            </div>
        </div>
        <div class="form-group" inp-name="email">
            <label for="txt-profile-email" class="col-sm-3 control-label">Correo electrónico</label>
            <div class="col-sm-9">
                <input type="email" id="txt-profile-email" class="form-control" value="{{ $usuario->email }}" disabled autocomplete="off">
            </div>
        </div>
        <div class="form-group" inp-name="fecha_nacimiento">
            <label for="txt-profile-fnac" class="col-sm-3 control-label">Fecha de nacimiento</label>
            <div class="col-sm-9 date-container">
                <input type="text" id="txt-profile-fnac" class="form-control" value="{{ date('d-m-Y', strtotime($usuario->fecha_nacimiento)) }}" disabled readonly autocomplete="off">
                <span class="glyphicon glyphicon-calendar"></span>
            </div>
        </div>
        <div class="form-group" inp-name="sexo">
            <label for="txt-profile-sexo" class="col-sm-3 control-label">Sexo</label>
            <div class="col-sm-9">
                <select id="txt-profile-sexo" class="form-control" disabled autocomplete="off">
                    @foreach($sexos as $id => $sexo)
                        <option value="{{ $id }}" {{ ($id === $usuario->id_sexo ? "selected" : "") }}>{{ $sexo }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="change-pass-container" style="text-align: right;">
            <a href="#" id="change-pass">Cambiar contraseña <span style="font-size: .7em;" class="glyphicon glyphicon-lock"></span></a>
        </div>
        <div class="form-group password-fields" inp-name="new_pw">
            <label for="txt-profile-newpass" class="col-sm-3 control-label">Nueva contraseña</label>
            <div class="col-sm-9">
                <input type="password" id="txt-profile-newpass" class="form-control" value="" autocomplete="off">
            </div>
        </div>
        <div class="form-group password-fields" inp-name="new_pwc">
            <label for="txt-profile-newpassc" class="col-sm-3 control-label">Confirmar contraseña</label>
            <div class="col-sm-9">
                <input type="password" id="txt-profile-newpassc" class="form-control" value="" autocomplete="off">
            </div>
        </div>
        <div class="form-group password-fields" inp-name="pw">
            <label for="txt-profile-pw" class="col-sm-3 control-label">Contraseña actual</label>
            <div class="col-sm-9">
                <input type="password" id="txt-profile-pw" class="form-control" value="" autocomplete="off">
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
        var changingPass = false;

        btnAllowEdit.data('active', false);
        btnIdPriv.data('selected', '{{ $usuario->id_privacidad_identificador }}');

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
                location.reload();
            });

            $('#btn-edit-ok').click(function () {
                guardarCambios();
            });

            $('#change-pass').click(function (e) {
                e.preventDefault();

                changingPass = true;

                $(this).parent().hide();

                $('.password-fields').show();
            });

            $('#profile-pic-remove').click(function () {
                eliminarImagenPerfil();
            });

            $('#profile-pic-edit').click(function () {
                $('<div class="div-profile-pic-upload">' +
                    '<input type="file" class="profile-pic-upload" accept="image/*">' +
                    '<sub>Sólo imágenes tipo jpeg, png, jpg y máximo 3MB.</sub>' +
                '</div>').dialog({
                    title: "Subir imagen de perfil nueva",
                    minWidth: 300,
                    width: 500,
                    resizable: false,
                    modal: true,
                    autoOpen: true,
                    classes: { 'ui-dialog': 'dialog-responsive' },
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
                                guardarImagenPerfil();
                            }
                        }
                    ]
                });
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

            $('.change-pass-container').show();
        }

        function guardarCambios() {
            var datos = {
                id_privacy: btnIdPriv.data('selected'),
                nombres: $('#txt-profile-nombres').val(),
                apellidos: $('#txt-profile-apellidos').val(),
                email: $('#txt-profile-email').val(),
                fecha_nacimiento: $('#txt-profile-fnac').val(),
                sexo: $('#txt-profile-sexo').val(),
                new_pw: $('#txt-profile-newpass').val(),
                new_pwc: $('#txt-profile-newpassc').val(),
                pw: $('#txt-profile-pw').val(),
                chaging_pass: (changingPass ? 1 : 0),
                _token: '{{ csrf_token() }}'
            };

            sendPost('{{ route('usuario.edit', [$usuario->id]) }}', datos, function (respuesta) {
                mensajes.alerta("Datos personales guardados correctamente.", "Guardado exitoso", function () {
                    location.reload();
                });
            });
        }

        function guardarImagenPerfil() {
            var input = $('.profile-pic-upload')[0];

            if (input.value !== "") {
                var url = '{{ route('usuario.uploadpic', [$usuario->id]) }}',
                    datos = {
                        input_img: input.files[0],
                        _token: '{{ csrf_token() }}'
                    };

                sendXhrPost(url, datos, function () {
                    mensajes.alerta("Imagen de perfil subida correctamente.", "Imagen de perfil", function () {
                        location.reload();
                    });
                });
            }
            else {
                mensajes.alerta("Por favor, seleccione una imagen antes de continuar.");
            }
        }

        function eliminarImagenPerfil() {
            mensajes.confirmacion_sino("¿Está seguro de eliminar la imagen de perfil?", function () {
                sendPost('{{ route('usuario.deletepic', [$usuario->id]) }}', {
                    _token: '{{ csrf_token() }}'
                }, function () {
                    mensajes.alerta('Imagen de perfil eliminada correctamente.', 'Aviso', function () {
                        location.reload();
                    });
                });
            });
        }
    </script>
@endsection