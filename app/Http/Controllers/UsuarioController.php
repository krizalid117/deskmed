<?php

namespace App\Http\Controllers;

use App\Usuario;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public static function downRut($rut) {
        return ltrim(str_replace(['.', '-'], ['', ''], $rut), '0');
    }

    public static function upRut($rut) {
        return number_format(substr($rut, 0, -1), 0, "", ".") . '-' . substr($rut, strlen($rut) - 1, 1);
    }
}
