<?php

namespace App\Http\Controllers;

use App\Usuario;
use App\Sexos;
use App\SolicitudesVerificacion;
use App\UsuarioAntecedentesFamiliares;
use App\IntegrantesNucleoFamiliar;
use App\TiposIdentificador;
use App\UsuarioEnfermedadesHistoricas;
use App\UsuarioEnfermedadesActuales;
use App\Http\Controllers\GlobalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function login() {
        return view('login');
    }

    public function register() {
        return view('registro', [
            "sexos" => Sexos::pluck('nombre', 'id'),
            "identificadores" => TiposIdentificador::pluck('nombre', 'id'),
        ]);
    }

    public function profile() {
        $usuario = Auth::user()["attributes"];

        return view('profile', [
            "usuario" => $usuario,
            "sexos" => Sexos::pluck('nombre', 'id'),
            "tipoIdentificador" => DB::table('tipos_identificador')->where('id', $usuario["id_tipo_identificador"])->value('nombre'),
            "profilePic" => UsuarioController::getProfilePic($usuario["profile_pic_path"], $usuario["id_sexo"]),
            "opcionesPrivacidad" => DB::table('privacidad_opciones')->get()
        ]);
    }

    public function profesion() {
        $usuario = Auth::user()["attributes"];

        return view('career', [
            "usuario" => $usuario,
            "id" => $usuario["id"],
            "isOwnUser" => true,
        ]);
    }

    public function doctorProfile(Request $request, $id) {
        return view('career', [
            "usuario" => Auth::user()["attributes"],
            "id" => $id,
            "isOwnUser" => false,
        ]);
    }

    //un usuario paciente ve su pripia ficha...
    public function ficha() {
        return view('ficha', $this->getDatosFicha(Auth::user()["attributes"]["id"], true));
    }

    //Un usuario ve la ficha de otro usuario paciente
    public function patientProfile(Request $request, $id) {
        return view('ficha', $this->getDatosFicha($id, false));
    }

    public function store(Request $request) {
        if ($request['tipo'] === "1") { //No se pueden crear usuarios administradores a través del formulario de registro
            return response()->json([ "error" => true ]);
        }

        $this->validate($request, [
            'tipo' => 'required|exists:tipos_usuario,id',
            'email' => 'required|email|max:100|unique:usuarios,email',
            'nombres' => 'required|max:100',
            'apellidos' => 'required|max:100',
            'identificador' => 'required|max:50|unique_with:usuarios,id_tipo_identificador = id_tipo_identificador',
            'id_tipo_identificador' => 'required|exists:tipos_identificador,id',
            'password' => 'required|max:50|min:6',
            'fecha_nacimiento' => 'required|max:10|date_format:"d-m-Y"',
            'sexo' => 'required|exists:sexos,id',
        ], [
            'identificador.unique_with' => 'El identificador "' . $request['identificador'] . '" ya existe para el tipo de identificación seleccionada.',
        ], [
            'tipo' => 'Tipo de usuario',
            'email' => 'Correo electrónico',
            'nombres' => 'Nombres',
            'apellidos' => 'Apellidos',
            'identificador' => 'Identificador',
            'id_tipo_identificador' => 'Tipo de identificador',
            'password' => 'Contraseña',
            'fecha_nacimiento' => 'Fecha de nacimiento',
            'sexo' => 'Sexo',
        ]);

        $datos = [
            'error' => false
        ];

        $usuario = new Usuario();

        $usuario->identificador = $request['id_tipo_identificador'] === "1" ? UsuarioController::downRut($request['identificador']) : $request['identificador'];
        $usuario->nombres = $request['nombres'];
        $usuario->apellidos = $request['apellidos'];
        $usuario->email = mb_strtolower($request['email'], 'utf8');
        $usuario->password = Hash::make($request['password']);
        $usuario->fecha_nacimiento = null;
        $usuario->id_tipo_usuario = $request['tipo'];
        $usuario->id_tipo_identificador = $request['id_tipo_identificador'];
        $usuario->fecha_nacimiento = $request["fecha_nacimiento"];
        $usuario->id_sexo = $request["sexo"];

        $nUsuariosMismoIdTipoId = DB::table('usuarios')->where('identificador', '=', $usuario->identificador)->where('id_tipo_identificador', '=', $usuario->id_tipo_identificador)->count();

        //Si no existe otro usuario con el mismo identificador y tipo de identificador...
        if (intval($nUsuariosMismoIdTipoId) === 0) {
            if ($usuario->save()) {

//                if (intval($usuario->id_tipo_usuario) !== 1) {
//                    $usuario->especialidades()->sync([
//                        1 => $request['especialidad']
//                    ], false);
//                }

                Auth::login($usuario);
            }
            else {
                $datos['error'] = true;
                $datos['mensaje'] = "Hubo un error al registrar el usuario. Por favor, intente de nuevo más tarde.";
            }
        }
        else {
            $ident = $usuario->id_tipo_identificador === "1" ? UsuarioController::upRut($usuario->identificador) : $usuario->identificador;

            $datos['error'] = true;
            $datos['mensaje'] = 'Ya existe un usuario con identificador "' . $ident . '" para el tipo de identificación seleccionada.';
        }

        return response()->json($datos);
    }

    public function signIn(Request $request) {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);

        $datos = [
            "error" => false,
            "logged_in" => false,
        ];

        if (Auth::attempt(['email' => mb_strtolower($request['email'], 'utf8'), 'password' => $request['password']], $request['remember'])) {
            $datos["logged_in"] = true;
        }

        return response()->json($datos);
    }

    public function logout() {
        Auth::logout();

        return redirect()->route('usuario.login');
    }

    public function edit(Request $request, $id) {

        $validar = [
            'id_privacy' => 'required|exists:privacidad_opciones,id',
            'nombres' => 'required|max:100',
            'apellidos' => 'required|max:100',
            'fecha_nacimiento' => 'required|max:10|date_format:"d-m-Y"',
            'sexo' => 'required|exists:sexos,id',
        ];

        $nombres = [
            'id_privacy' => 'Privacidad de identificador',
            'nombres' => 'Nombres',
            'apellidos' => 'Apellidos',
            'fecha_nacimiento' => 'Fecha de nacimiento',
            'sexo' => 'Sexo',
        ];

        $update = [
            'id_privacidad_identificador' => $request["id_privacy"],
            'nombres' => $request['nombres'],
            'apellidos' => $request['apellidos'],
            'email' => mb_strtolower($request['email'], 'utf8'),
            'fecha_nacimiento' => $request["fecha_nacimiento"],
            'id_sexo' => $request["sexo"],
        ];

        //Se agrega email en caso de que haya sido cambiado
        if (Auth::user()["attributes"]["email"] !== mb_strtolower($request["email"], 'utf8')) {
            $validar['email'] = 'required|email|max:100|unique:usuarios,email';
            $nombres['email'] = 'Correo eléctronico';
            $update['email'] = mb_strtolower($request["email"], 'utf8');
        }

        //si cambia de password, verificar que hayan datos en los 3 campos de password (nueva pass, confirmar nueva pass y pass actual), también revisar que la nueva pass  y la confirmación sean iguales
        if (intval($request["chaging_pass"]) === 1) {
            $validar['new_pw'] = 'required|max:50|min:6';
            $validar['new_pwc'] = 'required|same:new_pw';
            $validar['pw'] = 'required';

            $nombres['new_pw'] = 'Nueva contraseña';
            $nombres['new_pwc'] = 'Confirmar contraseña';
            $nombres['pw'] = 'Contraseña actual';

            $update['password'] = Hash::make($request["new_pw"]);
        }

        $this->validate($request, $validar, [], $nombres);

        $datos = [
            'error' => false
        ];

        $continuar = true;

        if (intval($request["chaging_pass"]) === 1 && !Hash::check($request["pw"], Auth::user()["attributes"]["password"])) {
            $continuar = false;
        }

        if ($continuar) {

            $update = DB::table('usuarios')
                ->where('id', $id)
                ->update($update);

            if (!$update) {
                $datos["error"] = true;
            }
        }
        else {
            $datos["error"] = true;
            $datos["mensaje"] = "La contraseña actual es incorrecta";
        }

        return response()->json($datos);
    }

    public function uploadPic(Request $request, $idUsuario) {

        $this->validate($request, [
            'input_img' => 'required|image|mimes:jpg,png,jpeg,pneg|max:3000',
        ], [
            'input_img.max' => 'La imagen no puede pesar más de 3MB.'
        ], [
            'input_img' => 'Imagen de perfil'
        ]);

        $datos = [
            'error' => false
        ];

        if ($request->hasFile('input_img')) {

            $image = $request->file('input_img');

            $name = hash("sha512", time()) . '.' . $image->getClientOriginalExtension();

            $destinationPath = public_path('profilePics');

            $image->move($destinationPath, $name);

            //Imagen antigua...
            $oldImage = Auth::user()["attributes"]["profile_pic_path"];

            $update = DB::table('usuarios')
                ->where('id', $idUsuario)
                ->update(['profile_pic_path' => $name]);

            if ($update) {
                //Se elimina imagen anterior
                if (File::exists(public_path('profilePics/' . $oldImage))) {
                    File::delete(public_path('profilePics/' . $oldImage));
                }
            }
            else {
                $datos["error"] = true;
            }
        }
        else {
            $datos["error"] = true;
            $datos["mensaje"] = "Por favor, incluir imagen a subir.";
        }

        return response()->json($datos);
    }

    public function deletePic(Request $request, $idUsuario) {

        $oldImage = Auth::user()["attributes"]["profile_pic_path"];

        $datos = [
            'error' => false
        ];

        $update = DB::table('usuarios')
            ->where('id', $idUsuario)
            ->update(['profile_pic_path' => null]);

        if ($update) {
            //Se elimina imagen anterior
            if (File::exists(public_path('profilePics/' . $oldImage))) {
                File::delete(public_path('profilePics/' . $oldImage));
            }
        }
        else {
            $datos["error"] = true;
        }

        return response()->json($datos);
    }

    public function sendVerification(Request $request) {
        $usuario = Auth::user()["attributes"];

        $idUsuario = $usuario["id"];
        $datos = [
            "error" => false,
            "mensaje" => "",
        ];

        if ($usuario["autenticidad_profesional_verificada"]) {
            $datos["error"] = true;
            $datos["mensaje"] = "Usted ya se encuentra verificado.";
        }
        else {
            $solicitudesUsuario = DB::table('solicitud_verificacion')->where('id_usuario', $idUsuario);
            $solicitudesAnteriores = $solicitudesUsuario->get();

            $datos["solicitudes_anteriores"] = $solicitudesAnteriores;

            $estado = 0; //0 = enviar, 1 = Ya hay solicitud pendiente, 2 = tiene más de 3 solicitudes rechazadas

            if ($solicitudesUsuario) {
                if (count($solicitudesAnteriores) > 0) {
                    $solicitudesPendientes = $solicitudesUsuario->where('estado', '0')->get();

                    if (count($solicitudesPendientes) > 0) {
                        $estado = 1;
                    } else {
                        $solicitudesRechazadas = $solicitudesUsuario->where('estado', '2')->get();

                        if (count($solicitudesRechazadas) > 2) {
                            $estado = 2;
                        }
                    }
                }
            } else {
                $datos["error"] = true;
            }

            if ($datos["error"] !== true) {
                switch ($estado) {
                    case 0: //Enviar

                        $solicitud = new SolicitudesVerificacion();

                        $solicitud->id_usuario = $idUsuario;

                        if ($solicitud->save()) {
                            $datos["mensaje"] = "Su solicitud ha sido enviada. Tendrá una respuesta en un plazo máximo de 48 horas hábiles. Le llegará un email de notificación cuando haya respuesta.";
                        }
                        else {
                            $datos["error"] = true;
                        }

                        break;
                    case 1: //Ya tiene solicitud pendiente

                        $datos["mensaje"] = "Ya hay una solicitud de verificación pendiente para su usuario";

                        break;
                    case 2: //Tiene 3 o más solicitudes rechazadas

                        $datos["mensaje"] = "Usted ya cuenta con 3 o más solicitudes de verificación rechazadas. Por favor vaya a la sección \"Soporte\" para conseguir ayuda.";

                        break;
                }
            }
        }

        return response()->json($datos);
    }

    //Perfil profesional
    public function guardarPPTemporal( Request $request) {
        $idUsuario = Auth::user()["attributes"]["id"];

        $this->validate($request, [
            'titulo' => 'max:255',
            'institucion' => 'max:255',
            'especialidad' => 'max:255',
            'nregistro' => 'max:100',
            'fregistro' => 'max:10|date_format:"d-m-Y"',
            'antecedente' => 'max:255',
        ], [

        ], [
            'titulo' => 'Título',
            'institucion' => 'Intitución',
            'especialidad' => 'Especialidad',
            'nregistro' => 'N° registro',
            'fregistro' => 'Fecha registro',
            'antecedente' => 'Antecedente de título',
        ]);

        $datos = [
            "error" => false,
        ];

        $update = DB::table('usuarios')
            ->where('id', $idUsuario)
            ->update([
                'titulo_segun_usuario' => $request["titulo"],
                'institucion_habilitante_segun_usuario' => $request["institucion"],
                'especialidad_segun_usuario' => $request["especialidad"],
                'nregistro_segun_usuario' => $request["nregistro"],
                'fecha_registro_segun_usuario' => $request["fregistro"],
                'antecedente_titulo_segun_usuario' => $request["antecedente"],
            ]);

        if (!$update) {
            $datos["error"] = true;
        }

        return response()->json($datos);
    }

    //guarda activación de antecedente familiar en ficha de salud (paciente)
    public function saveActivacionAntFam(Request $request) {
        $this->validate($request, [
            "id" => 'exists:antecedentes_familiares_opciones,id'
        ]);

        $idUsuario = Auth::user()["attributes"]["id"];

        $datos = [
            "error" => false,
            "mensaje" => "",
        ];

        if (intval($request["checked"]) === 1) { //insert

            $doesntExists = is_null(DB::table('usuario_antecedentes_familiares')->where('id_usuario', $idUsuario)->where('id_antecedentes_familiares_opciones', $request["id"])->first());

            if ($doesntExists) { //no existe registro, se inserta
                $insert = DB::table('usuario_antecedentes_familiares')->insertGetId([
                    "id_usuario" => $idUsuario,
                    "id_antecedentes_familiares_opciones" => $request["id"],
                    "created_at" =>  \Carbon\Carbon::now(), # \Datetime()
                    "updated_at" => \Carbon\Carbon::now(),  # \Datetime()
                ]);

                if ($insert) {
                    $datos["id"] = $insert;
                }
                else {
                    $datos["error"] = true;
                }
            }
        }
        else { //delete
            $exists = DB::table('usuario_antecedentes_familiares')->where('id_usuario', $idUsuario)->where('id_antecedentes_familiares_opciones', $request["id"])->first();

            if (!is_null($exists)) {

                $id = $exists->id;

                $del = UsuarioAntecedentesFamiliares::destroy($id);
            }
        }

        return response()->json($datos);
    }

    public function saveEspecificacionAntFam(Request $request)
    {
        $this->validate($request, [
            "id" => 'exists:usuario_antecedentes_familiares,id',
            "especificacion" => 'max:255'
        ]);

        $datos = [
            "error" => false,
            "mensaje" => "",
        ];

        $update = DB::table('usuario_antecedentes_familiares')
            ->where('id', $request["id"])
            ->update([
                "especificacion" => $request["especificacion"]
            ]);

        if (!$update) {
            $datos["error"] = true;
        }

        return response()->json($datos);
    }

    public function addEditIntegrante (Request $request) {
        $idUsuario = Auth::user()["attributes"]["id"];

        $validations = [
            "parentesco" => "required|exists:parentescos,id",
            "edad" => "required|integer|min:0|max:120",
            "estado_salud" => "required|exists:estados_salud,id",
        ];

        $messages = [];

        $names = [
            "parentesco" => "Parentesco",
            "edad" => "Edad",
            "estado_salud" => "Estado de salud",
        ];

        $insertArray = [
            "id_usuario" => $idUsuario,
            "id_parentesco" => $request["parentesco"],
            "edad" => $request["edad"],
            "id_estado_salud" => $request["estado_salud"],

        ];

        $updateArray = [
            "id_parentesco" => $request["parentesco"],
            "edad" => $request["edad"],
            "id_estado_salud" => $request["estado_salud"],
        ];

        if ($request["action"] === "edit") {
            $validations["id"] = "exists:integrantes_nucleo_familiar,id";
            $messages["id.exists"] = "El integrante no se puede editar ya que no existe.";
            $names["id"] = "Integrante";
        }

        if (intval($request["estado_salud"]) === 6) {
            $validations["edad_muerte"] = "required|integer|min:0|max:120";
            $names["edad_muerte"] = "Edad al morir";

            $validations["causa_muerte"] = "required|max:255";
            $names["causa_muerte"] = "Causa de muerte";

            $insertArray["edad_muerte"] = $request["edad_muerte"];
            $insertArray["causa_muerte"] = $request["causa_muerte"];

            $updateArray["edad_muerte"] = $request["edad_muerte"];
            $updateArray["causa_muerte"] = $request["causa_muerte"];
        }

        $this->validate($request, $validations, $messages, $names);

        $datos = [
            "error" => false,
            "mensaje" => "",
        ];

        if ($request["action"] === "add") { //insert
            $insert = DB::table('integrantes_nucleo_familiar')->insert($insertArray);

            if (!$insert) {
                $datos["error"] = true;
            }
        }
        else if ($request["action"] === "edit") { //update
            $update = DB::table('integrantes_nucleo_familiar')
                ->where('id', $request["id"])
                ->update($updateArray);

            if (!$update) {
                $datos["error"] = true;
            }
        }
        else {
            $datos["error"] = true;
            $datos["mensaje"] = "Acción no válida";
        }

        return response()->json($datos);
    }

    public function removerIntegrante(Request $request) {
        $datos = [
            "error" => false
        ];

        $delete = IntegrantesNucleoFamiliar::destroy($request["id"]);

        if (!$delete) {
            $datos["error"] = true;
        }

        return response()->json($datos);
    }

    public function cambioCondicion(Request $request) {
        $idUsuario = Auth::user()["attributes"]["id"];

        $datos = [
            "error" => false,
            "mensaje" => "",
        ];

        $tabla = "usuario_enfermedades_";

        if ($request["tipo"] === "actual") {
            $tabla .= "actuales";
        }
        else if ($request["tipo"] === "historica") {
            $tabla .= "historicas";
        }
        else {
            $datos["error"] = true;
            $datos["mensaje"] = "Tipo de condición inválida.";
        }

        if ($request["error"] !== true) {
            if ($request["accion"] === "add") {
                $insert = DB::table($tabla)->insert([
                    "id_usuario" => $idUsuario,
                    "id_enfermedad" => $request["id"],
                ]);

                if (!$insert) {
                    $datos["error"] = true;
                }
            }
            else if ($request["accion"] === "remove") {
                $detele = DB::table($tabla)->where('id_usuario', $idUsuario)->where('id_enfermedad', $request["id"])->delete();

                if (!$detele) {
                    $datos["error"] = true;
                }
            }
            else {
                $datos["error"] = true;
                $datos["mensaje"] = "Acción inválida.";
            }
        }

        return response()->json($datos);
    }

    public function cambioCondicionComentario(Request $request) {
        $this->validate($request, [
            "texto" => "max:500",
        ], [], [
            "texto" => "Comentario"
        ]);

        $datos = [
            "error" => false,
            "mensaje" => "",
        ];

        $campo = "comentario_condiciones_";

        if ($request["tipo"] === "actual") {
            $campo .= "actuales";
        }
        else if ($request["tipo"] === "historica") {
            $campo .= "historicas";
        }
        else {
            $datos["error"] = true;
            $datos["mensaje"] = "Tipo de condición inválida.";
        }

        $update = DB::table('usuarios')
            ->where('id', Auth::user()["attributes"]["id"])
            ->update([
                $campo => $request["texto"],
            ]);

        if (!$update) {
            $datos["error"] = true;
        }

        return response()->json($datos);
    }

    public static function downRut($rut) {
        return ltrim(str_replace(['.', '-'], ['', ''], $rut), '0');
    }

    public static function upRut($rut) {
        return number_format(substr($rut, 0, -1), 0, "", ".") . '-' . substr($rut, strlen($rut) - 1, 1);
    }

    public static function getProfilePic($path, $sex = 3) {
        $profilePic = "default_nonbinary.png";

        if (!is_null($path) && $path !== "" && File::exists(public_path("profilePics/{$path}"))) {
            $profilePic = $path;
        }
        else {
            if (!is_null($sex)) {
                if ($sex === 1) { //M
                    $profilePic = "default_male.png";
                }
                else if ($sex === 2) { //F
                    $profilePic = "default_female.png";
                }
            }
        }

        return $profilePic;
    }

    public static function getPrivacyIconClass($id) {
        $icon = "glyphicon glyphicon-eye-open";

        if (intval($id) === 2) {
            $icon = "glyphicon glyphicon-user";
        }
        else if (intval($id) === 3) {
            $icon = "glyphicon glyphicon-ban-circle";
        }

        return $icon;
    }

    private function getDatosFicha($id, $isOwnUser) {

        $nucleoFamiliar = DB::table('integrantes_nucleo_familiar as inf')
            ->join('parentescos as p', 'p.id', '=', 'inf.id_parentesco')
            ->join('estados_salud as e', 'e.id', '=', 'inf.id_estado_salud')
            ->where('id_usuario', '=', $id)
            ->select('inf.*', 'p.nombre as nombre_parentesco', 'e.nombre as nombre_estado')->get();

        $antecedentesFamiliares = DB::table('usuario_antecedentes_familiares as uaf')
            ->join('antecedentes_familiares_opciones as afo', 'uaf.id_antecedentes_familiares_opciones', '=', 'afo.id')
            ->where('uaf.id_usuario', '=', $id)
            ->select('afo.id', 'afo.nombre', 'uaf.especificacion', 'afo.necesita_especificacion', 'uaf.id as id_usuario_antecedente_familiar')->orderBy('afo.nombre', 'asc')->get();

        $enfermedades = DB::table('enfermedades_antecedentes_personales as eap')->orderBy('eap.nombre', 'asc')->get();

        $enfermedadesActualesUsuario = DB::table('enfermedades_antecedentes_personales as eap')
            ->join('usuario_enfermedades_actuales as uea', 'uea.id_enfermedad', '=', 'eap.id')
            ->where('uea.id_usuario', '=', $id)
            ->select('eap.id')
            ->get();

        $enfermedadesActuales = [];

        foreach ($enfermedadesActualesUsuario as $ea) {
            $enfermedadesActuales[] = $ea->id;
        }

        $enfermedadesHistoricasUsuario = DB::table('enfermedades_antecedentes_personales as eap')
            ->join('usuario_enfermedades_historicas as ueh', 'ueh.id_enfermedad', '=', 'eap.id')
            ->where('ueh.id_usuario', '=', $id)
            ->select('eap.id')
            ->get();

        $enfermedadesHistoricas = [];

        foreach ($enfermedadesHistoricasUsuario as $ea) {
            $enfermedadesHistoricas[] = $ea->id;
        }

        return [
            "id" => $id,
            "usuario" => Auth::user()["attributes"],
            "ant_fam_op" => DB::table('antecedentes_familiares_opciones')->orderBy('nombre', 'asc')->get(), //Opciones de antecedentes familiares
            "parentescos" => DB::table('parentescos')->orderBy('nombre', 'asc')->get(), //Opciones de parentesco
            "estadosSalud" => DB::table('estados_salud')->orderBy('id', 'asc')->get(), //Opciones de estado de salud
            "afu" => $antecedentesFamiliares, //afu = antecedentes familiares usuario
            "nucleoFamiliar" => $nucleoFamiliar->toArray(),
            "enfermedades" => $enfermedades,
            "enfermedadesActuales" => $enfermedadesActuales,
            "enfermedadesHistoricas" => $enfermedadesHistoricas,
            "isOwnUser" => $isOwnUser,
        ];
    }
}

//乇乂ㄒ尺卂 ㄒ卄丨匚匚
//乇乂ㄒ尺卂 ㄒ卂乂丨匚