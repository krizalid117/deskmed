@extends('layouts.master')

@section('title', '| Inicio de sesión')

@section('cssvariables')
    <link rel="stylesheet" href="{{ URL::to('css/variables_logged_out.css') }}">
@endsection

@section('stylesheets')
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
            padding: 5px 5px;
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

        .spn-msg {
            display: none;
            position: absolute;
            color: red;
            left: 0;
            text-align: left;
            width: 260px;
        }

        .login-input {
            -webkit-transition: border-color 300ms;
            -moz-transition: border-color 300ms;
            -ms-transition: border-color 300ms;
            -o-transition: border-color 300ms;
            transition: border-color 300ms;
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
                                <input type="text" name="deskmed-username" id="inp-usr" class="login-input">
                                <label for="inp-usr">Correo electrónico</label>
                                <span class="spn-msg" id="spn-usr-empty">Por favor, ingrese un correo electrónico</span>
                            </div>
                            <div class="login-input-group" style="position: relative;">
                                <input type="password" name="deskmed-password" id="inp-pw" class="login-input">
                                <label>Contraseña</label>
                                <span class="spn-msg" id="spn-pw-empty">Por favor, ingrese una contraseña</span>
                                <span class="spn-msg" id="spn-pwusr-wrong">Usuario y/o contraseña incorrectos</span>
                            </div>
                            <div class="pretty o-warning curvy hover a-rotate" style="float: left;">
                                <input type="checkbox" id="chk-remember">
                                <label for="chk-stay"><i class="glyphicon glyphicon-ok"></i> Recordarme</label>
                            </div>
                            <br>
                            <button class="btn btn-primary login-btn bold" id="login-ok">Iniciar sesión</button>
                            <p></p>
                            <a href="{{ route('usuario.register') }}" class="btn btn-success login-btn bold" id="register-ok">¡Quiero registrarme!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(function () {
            var inpUsername = $('#inp-usr');
            var inpPassword = $('#inp-pw');

            $('.login-input-group').children('input').focusout(function () {
                var texto = $(this).val();

                if (texto !== "") {
                    $(this).addClass('has-value');
                }
                else {
                    $(this).removeClass('has-value');
                }
            });

            $('#login-ok').click(function () {
                attempLogin();
            });

            inpUsername.add(inpPassword).keypress(function (e) {
                if (e.which === 13) {
                    attempLogin();
                }
            });

            function attempLogin() {
                var usernameText = $.trim(inpUsername.val());
                var passwordTxt = inpPassword.val();

                $('.spn-msg').hide();

                if (usernameText.length > 0) {
                    if (passwordTxt.length > 0) {
                        sendPost('{{ route('usuario.signin') }}', {
                            email: usernameText,
                            password: passwordTxt,
                            remember: $('#chk-remember').is(':checked') ? 1 : 0,
                            _token: '{{ csrf_token() }}'
                        }, function (datos) {
                            if (datos.logged_in) {
                                window.location.href = '{{ route('home') }}';
                            }
                            else {
                                inpUsername.css('border-color', 'red');
                                inpPassword.css('border-color', 'red').focus().select();

                                $('#spn-pwusr-wrong').fadeIn(300);

                                setTimeout(function () {
                                    inpUsername.css('border-color', '#a8a8a8');
                                    inpPassword.css('border-color', '#a8a8a8');

                                    $('#spn-pwusr-wrong').fadeOut(300);
                                }, 5000);
                            }
                        });
                    }
                    else {
                        inpPassword.css('border-color', 'red');
                        $('#spn-pw-empty').fadeIn(300);

                        setTimeout(function () {
                            inpPassword.css('border-color', '#a8a8a8');
                            $('#spn-pw-empty').fadeOut(300);
                        }, 5000);
                    }
                }
                else {
                    inpUsername.css('border-color', 'red');
                    $('#spn-usr-empty').fadeIn(300);

                    setTimeout(function () {
                        inpUsername.css('border-color', '#a8a8a8');
                        $('#spn-usr-empty').fadeOut(300);
                    }, 5000);
                }
            }
        });
    </script>
@endsection