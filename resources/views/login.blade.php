@extends('layouts.master')

@section('title')
    Deskmed - Inicio de sesión
@endsection

@section('local_css')
    <style>
        html, body {
            height: 100%;
        }

        .login-wrapper {
            height: 100%;
            width: 100%;
            display: flex;
            align-items: center;
        }

        .login-btn {
            width: 100%;
        }

        .login-input-group > input {
            border: none;
            border-bottom: solid 2px #a8a8a8;
            padding: 10px 5px;
            color: #333;
            font-size: 18px;
            display: inline-block;

            width: 100%;
            background-color: transparent;

            height: 35px;

            position: relative;
            z-index: 2;
        }

        .login-input-group {
            margin-bottom: 25px;
            position: relative;
        }

        .login-input-group > label {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            color: #a8a8a8;
            font-weight: 100;

            position: absolute;
            top: 35%;
            left: 0;
            z-index: 1;

            transition: all 300ms ease;
        }

        .login-input-group > input:focus + label,
        .login-input-group > input.has-value + label {
            top: -35%;
            font-size: 12px;
            font-style: italic;
        }

        #register-ok {
            transition: all 350ms ease;
        }

        #register-ok:hover {
            font-size: 16px;
            padding: 5px 12px;
        }
    </style>
@endsection

@section('content')
    <div class="login-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 text-center">
                    <div class="panel panel-deskmed">
                        <div class="panel-heading">
                            Inicia sesión con tu cuenta <span class="bolder">DeskMed&trade;</span>
                        </div>
                        <div class="panel-body">
                            <br>
                            <div class="login-input-group">
                                <input type="text" name="deskmed-username">
                                <label>Correo electrónico</label>
                            </div>
                            <div class="login-input-group">
                                <input type="password" name="deskmed-password">
                                <label>Contraseña</label>
                            </div>
                            <button class="btn btn-primary login-btn bold" id="login-ok">Iniciar sesión</button>
                            <p></p>
                            <a href="{{ route('usuario.registro') }}" class="btn btn-success login-btn bold" id="register-ok">¡Quiero registrarme!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(function () {
            $('.login-input-group').children('input').focusout(function () {
                var texto = $(this).val();

                if (texto !== "") {
                    $(this).addClass('has-value');
                }
                else {
                    $(this).removeClass('has-value');
                }
            });
        });
    </script>
@endsection