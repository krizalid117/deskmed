<?php

namespace App\Http\Controllers;

use App\Usuario;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    public function register() {
        return view('registro');
    }

    public function create(Request $request) {
        $this->validate($request, [
            [
                "tipo" => "required|exists:tipos_usuario,id",
                "email" => "required|email|max:100|unique:usuarios,email",
                "nombres" => "required|max:100",
                "apellidos" => "required|max:100",
                "identificador" => "required|max:50",
                "tipo_identificador" => "required|exists:tipos_identificador,id",
                "especialidad" => "required_if:tipo,2|exists:especialidades_medicas,id",
                "password" => "required|max:50|min:6",
            ], [
                "especialidad.required_if" => 'El campo Especialidad es obligatorio'
            ], [
                "tipo" => "Tipo de usuario",
                "email" => "Correo electrónico",
                "nombres" => "Nombres",
                "apellidos" => "Apellidos",
                "identificador" => "Identificador",
                "tipo_identificador" => "Tipo de identificador",
                "especialidad" => "Especialidad",
                "password" => "Contraseña",
            ]
        ]);

        $data = [
            "error" => false
        ];

        $usuario = new Usuario();

        $usuario->identificador = $request["identificador"];
        $usuario->nombres = $request["nombres"];
        $usuario->apellidos = $request["apellidos"];
        $usuario->email = $request["email"];
        $usuario->password = hash('sha512', $request["password"]);
        //$usuario->fecha_nacimiento = $request["fecha_nacimiento"];
        $usuario->id_tipo_usuario = $request["id_tipo_usuario"];
        $usuario->id_tipo_identificador = $request["id_tipo_identificador"];

        if (!$usuario->save()) {
            $datos["error"] = true;
        }

        return response()->json($data);
    }
}
