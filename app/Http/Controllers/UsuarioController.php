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
            'identificador.unique_with' => 'El identificador ":value" ya existe para el tipo de identificación seleccionada.',
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

        $data = [
            'error' => false
        ];

        $usuario = new Usuario();

        $usuario->identificador = $request['id_tipo_identificador'] === "1" ? Usuario::downRut($request['identificador']) : $request['identificador'];
        $usuario->nombres = $request['nombres'];
        $usuario->apellidos = $request['apellidos'];
        $usuario->email = $request['email'];
        $usuario->password = bcrypt($request['password']);
        $usuario->fecha_nacimiento = null;
        $usuario->id_tipo_usuario = $request['tipo'];
        $usuario->id_tipo_identificador = $request['id_tipo_identificador'];

        $nUsuariosMismoIdTipoId = DB::table('usuarios')->where('identificador', '=', $usuario->identificador)->where('id_tipo_identificador', '=', $usuario->id_tipo_identificador)->count();

//        dd($nUsuariosMismoIdTipoId);

        //Si no existe otro usuario con el mismo identificador y tipo de identificador...
        if (intval($nUsuariosMismoIdTipoId) === 0) {
            if ($usuario->save()) {

                $usuario->especialidades()->sync([
                    1 => $request['especialidad']
                ], false);

                Auth::login($usuario);
            }
            else {
                $datos['error'] = true;
            }
        }
        else {
            $datos['error'] = true;
            $datos['mensaje'] = "Ya existe un usuario con identificador \"$usuario->identificador\" para el tipo de identificación seleccionada.";
        }

        return response()->json($data);
    }

    public function logout() {
        Auth::logout();

        return redirect()->route('usuario.login');
    }
}
