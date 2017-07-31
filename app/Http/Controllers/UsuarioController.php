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
                "tipo" => "required"
            ], [

            ], [

            ]
        ]);
    }
}
