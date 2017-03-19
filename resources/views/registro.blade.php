@extends('layouts.master')

@section('title')
    Deskmed - Registro de cuenta
@endsection

@section('local_head')
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

        .id-tipo .ui-icon-check {
            display: none;
        }

        .id-tipo-selected {
            background-color: #f1f1f1;
        }

        .id-tipo-selected .ui-icon-check {
            display: inline-block;
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
                                <span class="bold">Registro de Pacientes</span>
                                <form>
                                    <div class="form-group">
                                        <label for="paciente-email" class="control-label col-xs-12">Correo electrónico</label>
                                        <div class="col-xs-12">
                                            <input type="email" class="form-control" id="paciente-email" placeholder="correo@ejemplo.com">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="paciente-nombres" class="control-label col-xs-12">Nombres</label>
                                        <div class="col-xs-12">
                                            <input type="text" class="form-control" id="paciente-nombres" placeholder="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="paciente-apellidos" class="control-label col-xs-12">Apellidos</label>
                                        <div class="col-xs-12">
                                            <input type="text" class="form-control" id="paciente-apellidos" placeholder="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="paciente-identificador" class="control-label col-xs-12">Identificador</label>
                                        <div class="col-xs-12">
                                            <div class="input-group">
                                                <div class="input-group-btn">
                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                        <span class="id-tipo-sel-text">RUT</span> <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a href="#" class="id-tipo id-tipo-selected" data-tipo="rut">
                                                                <span class="id-tipo-text">RUT</span>
                                                                <span class="ui-icon ui-icon-check" style="float: right; margin-top: 1px;"></span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="id-tipo" data-tipo="pasaporte">
                                                                <span class="id-tipo-text">Pasaporte</span>
                                                                <span class="ui-icon ui-icon-check" style="float: right; margin-top: 1px;"></span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <input type="text" class="form-control" id="paciente-identificador">
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                            <div style="display: none;" class="register-content reg-count-3">
                                <form>
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <input type="email" class="form-control" id="paciente-email">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer clearfix">
                        <button id="btn-register-continue" class="btn btn-primary" style="float: right;" data-step="1" disabled>Continuar &rightarrow;</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(function () {
            $('.register-option').not('.register-option-selected').click(function () {
                $('.register-option-selected').removeClass('register-option-selected');

                $(this).addClass('register-option-selected');

                $('#btn-register-continue').prop('disabled', false);
            });

            //Se selecciona un tipo de cuenta
            $('#btn-register-continue').click(function () {
                var $btn = $(this);

                if ($btn.data('step') === 1) {
                    var selected = $('.register-option-selected');

                    if (selected.length) {
                        var nextContainer = (selected.data('tipo') === "paciente") ? $('.reg-count-2') : $('.reg-count-3');

                        $('.reg-count-1').toggle("slide", {direction: "left"}, 500);
                        nextContainer.toggle("slide", {direction: "right"}, 500, function () {
                            nextContainer.find('input:eq(0)').focus();
                        });

                        $btn.text('Finalizar Registro');
                        $btn.data('step', 2);

                        $('.head-registro-text').text('Formulario de registro')
                    }
                    else {
                        alerta('Por favor, seleccione un tipo de cuenta a crear.', 'Aviso');
                    }
                }
                else { //Finalización de registro

                }
            });

            $('.id-tipo').click(function () {
                var $this = $(this);

                $('.id-tipo-selected').removeClass('id-tipo-selected');

                $this.addClass('id-tipo-selected');

                $('.id-tipo-sel-text').text($this.find('.id-tipo-text').text());

                $('#paciente-identificador').data('tipo', $this.data('tipo'));
            });
        });
    </script>

@endsection