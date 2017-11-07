<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Usuario;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function createUsers(Request $request, $amaunt) {
        $usuario = Auth::user();

        $users = factory(Usuario::class, intval($amaunt))->create();

        return view('test', [
            "usuario" => $usuario,
            "results" => $users,
        ]);
    }
}
