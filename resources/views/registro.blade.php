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
            use Illuminate\Support\Facades\DB;
            use Illuminate\Support\Facades\Session;

            $identificadores = TiposIdentificador::pluck('nombre', 'id');
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
                                        <label for="paciente-password" class="control-label col-xs-12">Contraseña</label>
                                        <div class="col-xs-12">
                                            <input type="password" class="form-control" id="paciente-password" placeholder="">
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
                                                <input type="text" class="form-control inp-id" id="paciente-identificador" data-tipo="1">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="paciente-fnac" class="control-label col-xs-12">Fecha nacimiento</label>
                                        <div class="col-xs-12">
                                            <input type="text" id="paciente-fnac" class="form-control">
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
                                        <label for="doctor-password" class="control-label col-xs-12">Contraseña</label>
                                        <div class="col-xs-12">
                                            <input type="password" class="form-control" id="doctor-password" placeholder="">
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
                                                <input type="text" class="form-control inp-id" id="doctor-identificador" data-tipo="1">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="doctor-fnac" class="control-label col-xs-12">Fecha nacimiento</label>
                                        <div class="col-xs-12">
                                            <input type="text" id="doctor-fnac" class="form-control">
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
@endsection

@section('scripts')
    <script type="text/javascript">
        $(function () {

            $('#doctor-fnac').datepicker({
                maxDate: "{{ date('d-m-Y') }}"
            });

            $('#paciente-fnac').datepicker({
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
                        var nextContainer = esPaciente ? $('.reg-count-2') : $('.reg-count-3');

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
                        email: esPaciente ? $('#paciente-email').val() : $('#doctor-email').val(),
                        nombres: esPaciente ? $('#paciente-nombres').val() : $('#doctor-nombres').val(),
                        apellidos: esPaciente ? $('#paciente-apellidos').val() : $('#doctor-apellidos').val(),
                        identificador: esPaciente ? $('#paciente-identificador').val() : $('#doctor-identificador').val(),
                        id_tipo_identificador: esPaciente ? $('.id-tipo-paciente-selected').data('tipo') : $('.id-tipo-doctor-selected').data('tipo'),
                        fecha_nacimiento: esPaciente ? $('#paciente-fnac').val('') : $('#doctor-fnac').val(''),
                        password: esPaciente ? $('#paciente-password').val() : $('#doctor-password').val()
                    };

                    sendPost('{{ route('usuario.signup') }}', datos, function () {
                        window.location.href = '{{ route('home') }}';
                    });
                }
            });

            $('.id-tipo-paciente').click(function () {
                var $this = $(this);

                $('.id-tipo-paciente-selected').removeClass('id-tipo-paciente-selected');

                $this.addClass('id-tipo-paciente-selected');

                $('.id-tipo-paciente-sel-text').text($this.find('.id-tipo-text').text());

                $('#paciente-identificador').data('tipo', $this.data('tipo')).blur();
            });

            $('.id-tipo-doctor').click(function () {
                var $this = $(this);

                $('.id-tipo-doctor-selected').removeClass('id-tipo-doctor-selected');

                $this.addClass('id-tipo-doctor-selected');

                $('.id-tipo-doctor-sel-text').text($this.find('.id-tipo-text').text());

                $('#doctor-identificador').data('tipo', $this.data('tipo')).blur();
            });
        });
    </script>
@endsection