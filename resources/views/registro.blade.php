@extends('layouts.master')

@section('title')
    Deskmed - Registro de cuenta
@endsection

@section('local_css')
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
    </style>
@endsection

@section('content')

    <div class="container" style="padding-top: 30px;">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                <div class="panel panel-deskmed">
                    <div class="panel-heading text-center">
                        ¿Qué tipo de cuenta deseas crear?
                    </div>
                    <div class="panel-body clearfix">
                        <p style="text-align: justify;">
                            En DeskMed puedes ser paciente y profesional de la salud al mismo tiempo, pero necesitamos saber cuáles serás las actividades principales en tu cuenta.<br><br>Por favor, selecciona el tipo de cuenta principal que piensas utilizar:
                        </p>

                        <hr>

                        <div class="register-option">
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
                        <div class="register-option">
                            <table>
                                <tr>
                                    <td style="width: 25%; text-align: center;">
                                        <img src="{{ url('img/doctor-icon.png') }}" class="register-option-icon">
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

                        <hr>

                        <button class="btn btn-primary" style="float: right;">Continuar &rightarrow;</button>
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
            });
        });
    </script>

@endsection