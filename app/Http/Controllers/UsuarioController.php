<?php

namespace App\Http\Controllers;

use App\Usuario;
use App\Sexos;
use App\Http\Controllers\GlobalController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class UsuarioController extends Controller
{
    public function login() {
        return view('login');
    }

    public function register() {
        return view('registro');
    }

    public function store(Request $request) {
        $this->validate($request, [
            'tipo' => 'required|exists:tipos_usuario,id',
            'email' => 'required|email|max:100|unique:usuarios,email',
            'nombres' => 'required|max:100',
            'apellidos' => 'required|max:100',
            'identificador' => 'required|max:50|unique_with:usuarios,id_tipo_identificador = id_tipo_identificador',
            'id_tipo_identificador' => 'required|exists:tipos_identificador,id',
            'especialidad' => 'required_if:tipo,2|exists:especialidades_medicas,id',
            'password' => 'required|max:50|min:6',
        ], [
            'especialidad.required_if' => 'El campo "Especialidad" es obligatorio.',
            'identificador.unique_with' => 'El identificador "' . $request['identificador'] . '" ya existe para el tipo de identificación seleccionada.',
        ], [
            'tipo' => 'Tipo de usuario',
            'email' => 'Correo electrónico',
            'nombres' => 'Nombres',
            'apellidos' => 'Apellidos',
            'identificador' => 'Identificador',
            'tipo_identificador' => 'Tipo de identificador',
            'especialidad' => 'Especialidad',
            'password' => 'Contraseña',
        ]);

        $datos = [
            'error' => false
        ];

        $usuario = new Usuario();

        $usuario->identificador = $request['id_tipo_identificador'] === "1" ? UsuarioController::downRut($request['identificador']) : $request['identificador'];
        $usuario->nombres = $request['nombres'];
        $usuario->apellidos = $request['apellidos'];
        $usuario->email = mb_strtolower($request['email'], 'utf8');
        $usuario->password = bcrypt($request['password']);
        $usuario->fecha_nacimiento = null;
        $usuario->id_tipo_usuario = $request['tipo'];
        $usuario->id_tipo_identificador = $request['id_tipo_identificador'];

        $nUsuariosMismoIdTipoId = DB::table('usuarios')->where('identificador', '=', $usuario->identificador)->where('id_tipo_identificador', '=', $usuario->id_tipo_identificador)->count();

        //Si no existe otro usuario con el mismo identificador y tipo de identificador...
        if (intval($nUsuariosMismoIdTipoId) === 0) {
            if ($usuario->save()) {

                if (intval($usuario->id_tipo_usuario) !== 1) {
                    $usuario->especialidades()->sync([
                        1 => $request['especialidad']
                    ], false);
                }

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

        $this->validate($request, $validar, [], $nombres);

        $datos = [
            'error' => false
        ];

        $update = DB::table('usuarios')
            ->where('id', $id)
            ->update($update);

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
}
