<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsuarioDoctores extends Model
{
    protected $table = "usuario_doctores";

    public function patient() {
        return $this->belongsTo('App\Usuario', 'id_usuario');
    }

    public function doctor() {
        return $this->belongsTo('App\Usuario', 'id_usuario_doctor');
    }
}
