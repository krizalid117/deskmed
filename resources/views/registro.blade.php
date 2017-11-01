@extends('layouts.master')

@section('title', '| Registro de cuenta')

@section('cssvariables')
    <link rel="stylesheet" href="{{ URL::to('css/variables_logged_out.css') }}">
@endsection

@section('stylesheets')
    <style>
        .register-option > table {
            width: 100%;
        }

        .register-option {
            padding: 10px 0;
            cursor: pointer;

            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;

            border: 1px solid transparent;
        }

        .register-option:hover {
            background-color: #f1f1f1;
        }

        .register-option-icon {
            width: 100px;
            height: 100px;
            /*border: 1px solid black;*/
        }

        .register-option-title {
            font-weight: bold;
        }

        .register-option-desc {
            text-align: justify;
        }

        .register-option-title
        , .register-option-desc {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .register-option-selected {
            border: 1px solid #337ab7;
            background-color: #f1f1f1;
        }

        .register-container {
            display: flex !important;
            width: auto;
        }

        .register-content {
            width: 100%;
            flex-grow: 1;
        }

        .register-content .form-group > label {
            margin-top: 10px;
        }

        .id-tipo-usuario .ui-icon-check {
            display: none;
        }

        .id-tipo-usuario-selected {
            background-color: #f1f1f1;
        }

        .id-tipo-usuario-selected .ui-icon-check {
            display: inline-block;
        }

        .panel-footer {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .panel-footer > a {
            margin-right: auto;
        }
    </style>
@endsection

@section('content')
    <div class="container" style="padding-top: 30px;">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                <div class="panel panel-deskmed">
                    <div class="panel-heading text-center">
                        <span class="head-registro-text">¿Qué tipo de cuenta deseas crear?</span>
                    </div>
                    <div class="panel-body">
                        <div class="register-container clearfix">
                            <div class="register-content reg-count-1">
                                <p style="text-align: justify;">
                                    En DeskMed puedes ser paciente y profesional de la salud al mismo tiempo, pero necesitamos saber cuáles serás las actividades principales en tu cuenta.<br><br>Por favor, selecciona el tipo de cuenta principal que piensas utilizar:
                                </p>

                                <hr>

                                <div class="register-option" data-tipo="paciente">
                                    <table>
                                        <tr>
                                            <td style="width: 25%; text-align: center;">
                                                <img src="{{ url('img/paciente-icon.png') }}" class="register-option-icon">
                                            </td>
                                            <td style="padding: 10px;">
                                                <div class="register-option-title">Deseo crear una cuenta como Paciente</div>
                                                <p class="register-option-desc hidden-xs">
                                                    Persona natural que busca consultas en línea con profesionales que puedan ayudarle con sus problemas de salud. Se verifica identificación nacional.
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <br>
                                <div class="register-option" data-tipo="doctor">
                                    <table>
                                        <tr>
                                            <td style="width: 25%; text-align: center;">
                                                &nbsp;&nbsp;&nbsp;<img src="{{ url('img/doctor-icon.png') }}" class="register-option-icon">
                                            </td>
                                            <td style="padding: 10px;">
                                                <div class="register-option-title">Deseo crear una cuenta como Profesional de la salud</div>
                                                <p class="register-option-desc hidden-xs">
                                                    Profesional con estudios afines a la salud. Su formación académica y experiencia serán comprobadas antes de que pueda realizar consultas en línea.
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div style="display: none;" class="register-content reg-count-2">
                                {{--<span class="bold">Registro de Pacientes</span>--}}
                                <div class="form-group" inp-name="email">
                                    <label for="usuario-email" class="control-label col-xs-12">Correo electrónico</label>
                                    <div class="col-xs-12">
                                        <input type="email" class="form-control" id="usuario-email" placeholder="correo@ejemplo.com">
                                    </div>
                                </div>
                                <div class="form-group" inp-name="password">
                                    <label for="usuario-password" class="control-label col-xs-12">Contraseña</label>
                                    <div class="col-xs-12">
                                        <input type="password" class="form-control" id="usuario-password" placeholder="">
                                    </div>
                                </div>
                                <div class="form-group" inp-name="nombres">
                                    <label for="usuario-nombres" class="control-label col-xs-12">Nombres</label>
                                    <div class="col-xs-12">
                                        <input type="text" class="form-control" id="usuario-nombres" placeholder="">
                                    </div>
                                </div>
                                <div class="form-group" inp-name="apellidos">
                                    <label for="usuario-apellidos" class="control-label col-xs-12">Apellidos</label>
                                    <div class="col-xs-12">
                                        <input type="text" class="form-control" id="usuario-apellidos" placeholder="">
                                    </div>
                                </div>
                                <div class="form-group" inp-name="identificador">
                                    <label for="usuario-identificador" class="control-label col-xs-12">Identificador</label>
                                    <div class="col-xs-12">
                                        <div class="input-group">
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                    <span class="id-tipo-usuario-sel-text">{{ $identificadores[1] }}</span> <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <?php
                                                        $count = 0;

                                                        foreach ($identificadores as $id => $identificador) {
                                                    ?>
                                                        <li>
                                                            <a href="#" class="id-tipo-usuario <?php echo ($count === 0 ? "id-tipo-usuario-selected" : ""); ?>" data-tipo="{{ $id }}">
                                                                <span class="id-tipo-text">{{ $identificador }}</span>
                                                                <span class="ui-icon ui-icon-check" style="float: right; margin-top: 1px;"></span>
                                                            </a>
                                                        </li>
                                                    <?php
                                                            $count++;
                                                        }
                                                    ?>
                                                </ul>
                                            </div>
                                            <input type="text" class="form-control inp-id" id="usuario-identificador" data-tipo="1">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" inp-name="fecha_nacimiento">
                                    <label for="usuario-fnac" class="control-label col-xs-12">Fecha nacimiento</label>
                                    <div class="col-xs-12">
                                        <input type="text" id="usuario-fnac" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group" inp-name="sexo">
                                    <label for="usuario-gen" class="control-label col-xs-12">Sexo</label>
                                    <div class="col-xs-12">
                                        <select id="usuario-gen" class="form-control">
                                            @foreach($sexos as $id => $sexo)
                                                <option value="{{ $id }}">{{ $sexo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer clearfix">
                        <a href="{{ route('usuario.login') }}">Atrás</a>
                        <button id="btn-register-continue" class="btn btn-primary" data-step="1" disabled>Continuar &rightarrow;</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(function () {

            $('#usuario-fnac').datepicker({
                maxDate: "{{ date('d-m-Y') }}"
            });

            $('.inp-id').RutValidation(function () {
                return parseInt($(this).data('tipo')) === 1; //Hará la validación sólo si el data "tipo" es 1 (tipo de identificador rut).
            });

            $('.register-option').not('.register-option-selected').click(function () {
                $('.register-option-selected').removeClass('register-option-selected');

                $(this).addClass('register-option-selected');

                $('#btn-register-continue').prop('disabled', false);
            });

            //Se selecciona un tipo de cuenta
            $('#btn-register-continue').click(function () {
                var $btn = $(this),
                    selected = $('.register-option-selected'),
                    esPaciente = (selected.data('tipo') === "paciente");

                if ($btn.data('step') === 1) {

                    if (selected.length) {
                        var nextContainer = $('.reg-count-2');

                        $('.reg-count-1').toggle("slide", { direction: "left" }, 500);

                        nextContainer.toggle("slide", { direction: "right" }, 500, function () {
                            nextContainer.find('input:eq(0)').focus();
                        });

                        $btn.text('Finalizar Registro');
                        $btn.data('step', 2);

                        $('.head-registro-text').text('Formulario de registro de ' + (esPaciente ? "pacientes" : "doctores"));
                    }
                    else {
                        mensajes.alerta('Por favor, seleccione un tipo de cuenta a crear.', 'Aviso');
                    }
                }
                else { //Finalización de registro
                    var datos = {
                        _token: '{{ csrf_token() }}',
                        tipo: esPaciente ? 3 : 2,
                        email: $('#usuario-email').val(),
                        nombres: $('#usuario-nombres').val(),
                        apellidos: $('#usuario-apellidos').val(),
                        identificador: $('#usuario-identificador').val(),
                        id_tipo_identificador: $('.id-tipo-usuario-selected').data('tipo'),
                        fecha_nacimiento: $('#usuario-fnac').val(),
                        sexo: $('#usuario-gen').val(),
                        password: $('#usuario-password').val()
                    };

                    sendPost('{{ route('usuario.signup') }}', datos, function () {
                        window.location.href = '{{ route('home') }}';
                    });
                }
            });

            $('.id-tipo-usuario').click(function () {
                var $this = $(this);

                $('.id-tipo-usuario-selected').removeClass('id-tipo-usuario-selected');

                $this.addClass('id-tipo-usuario-selected');

                $('.id-tipo-usuario-sel-text').text($this.find('.id-tipo-text').text());

                $('#usuario-identificador').data('tipo', $this.data('tipo')).blur();
            });
        });
    </script>
@endsection