<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tiposUsuario extends Model
{
    //
    protected $table = "tipos_usuario";

    public function usuarios() {
        return $this->hasMany('App\Usuarios');
    }
}
