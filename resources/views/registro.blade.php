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

        .id-tipo-paciente .ui-icon-check,
        .id-tipo-doctor .ui-icon-check,
        .especialidad .ui-icon-check {
            display: none;
        }

        .id-tipo-doctor-selected,
        .id-tipo-paciente-selected,
        .especialidad-selected {
            background-color: #f1f1f1;
        }

        .id-tipo-doctor-selected .ui-icon-check,
        .id-tipo-paciente-selected .ui-icon-check,
        .especialidad-selected .ui-icon-check {
            display: inline-block;
        }
    </style>
@endsection

@section('content')

    <?php
            use App\TiposIdentificador;
            use App\EspecialidadesMedicas;

            $identificadores = TiposIdentificador::pluck('nombre', 'id');

            $especialidades = EspecialidadesMedicas::pluck('nombre', 'id');
    ?>

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
                                                        <span class="id-tipo-paciente-sel-text">{{ $identificadores[1] }}</span> <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <?php
                                                            $count = 0;

                                                            foreach ($identificadores as $id => $identificador) {
                                                        ?>
                                                            <li>
                                                                <a href="#" class="id-tipo-paciente <?php echo ($count === 0 ? "id-tipo-paciente-selected" : ""); ?>" data-tipo="{{ $id }}">
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
                                                <input type="text" class="form-control" id="paciente-identificador">
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                            <div style="display: none;" class="register-content reg-count-3">
                                <form>
                                    <div class="form-group">
                                        <label for="paciente-email" class="control-label col-xs-12">Correo electrónico</label>
                                        <div class="col-xs-12">
                                            <input type="email" class="form-control" id="doctor-email" placeholder="correo@ejemplo.com">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="paciente-nombres" class="control-label col-xs-12">Nombres</label>
                                        <div class="col-xs-12">
                                            <input type="text" class="form-control" id="doctor-nombres" placeholder="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="paciente-apellidos" class="control-label col-xs-12">Apellidos</label>
                                        <div class="col-xs-12">
                                            <input type="text" class="form-control" id="doctor-apellidos" placeholder="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="doctor-identificador" class="control-label col-xs-12">Identificador</label>
                                        <div class="col-xs-12">
                                            <div class="input-group">
                                                <div class="input-group-btn">
                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                        <span class="id-tipo-doctor-sel-text">{{ $identificadores[1] }}</span> <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <?php
                                                            $count = 0;

                                                            foreach ($identificadores as $id => $identificador) {
                                                        ?>
                                                            <li>
                                                                <a href="#" class="id-tipo-doctor <?php echo ($count === 0 ? "id-tipo-doctor-selected" : ""); ?>" data-tipo="{{ $id }}">
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
                                                <input type="text" class="form-control" id="paciente-identificador">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="paciente-nombres" class="control-label col-xs-12">Especialidad</label>
                                        <div class="dropdown col-xs-12">
                                            <button class="btn btn-default dropdown-toggle" type="button" id="dd-esp" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="width: 100%; text-align: left; position: relative;" data-tipo="1">
                                                <span id="especialidad-sel-text">{{ $especialidades[1] }}</span>
                                                <span class="caret" style="position: absolute; right: 8px; top: 15px;"></span>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dd-esp" style="max-height: 200px; overflow-y: auto; top: 94%; left: 14px; width: 94.5%;">
                                                <?php
                                                    $count = 0;

                                                    foreach ($especialidades as $id => $especialidad) {
                                                ?>
                                                    <li>
                                                        <a href="#" class="especialidad <?php echo ($count === 0 ? "especialidad-selected" : ""); ?>" data-tipo="{{ $id }}">
                                                            <span class="especialidad-text">{{ $especialidad }}</span>
                                                            <span class="ui-icon ui-icon-check" style="float: right; margin-top: 1px;"></span>
                                                        </a>
                                                    </li>
                                                <?php
                                                        $count++;
                                                    }
                                                ?>
                                            </ul>
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
                        var esPaciente = (selected.data('tipo') === "paciente"),
                            nextContainer = esPaciente ? $('.reg-count-2') : $('.reg-count-3');

                        $('.reg-count-1').toggle("slide", { direction: "left" }, 500);

                        nextContainer.toggle("slide", { direction: "right" }, 500, function () {
                            nextContainer.find('input:eq(0)').focus();
                        });

                        $btn.text('Finalizar Registro');
                        $btn.data('step', 2);

                        $('.head-registro-text').text('Formulario de registro de ' + (esPaciente ? "pacientes" : "doctores"));
                    }
                    else {
                        alerta('Por favor, seleccione un tipo de cuenta a crear.', 'Aviso');
                    }
                }
                else { //Finalización de registro

                }
            });

            $('.id-tipo-paciente').click(function () {
                var $this = $(this);

                $('.id-tipo-paciente-selected').removeClass('id-tipo-paciente-selected');

                $this.addClass('id-tipo-paciente-selected');

                $('.id-tipo-paciente-sel-text').text($this.find('.id-tipo-text').text());

                $('#paciente-identificador').data('tipo', $this.data('tipo'));
            });

            $('.id-tipo-doctor').click(function () {
                var $this = $(this);

                $('.id-tipo-doctor-selected').removeClass('id-tipo-doctor-selected');

                $this.addClass('id-tipo-doctor-selected');

                $('.id-tipo-doctor-sel-text').text($this.find('.id-tipo-text').text());

                $('#doctor-identificador').data('tipo', $this.data('tipo'));
            });

            $('.especialidad').click(function () {
                var $this = $(this);

                $('.especialidad-selected').removeClass('especialidad-selected');

                $this.addClass('especialidad-selected');

                $('#especialidad-sel-text').text($this.find('.especialidad-text').text());

                $('#dd-esp').data('tipo', $this.data('tipo'));
            });
        });
    </script>

@endsection