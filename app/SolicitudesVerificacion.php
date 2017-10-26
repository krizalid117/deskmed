<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SolicitudesVerificacion extends Model
{
    protected $table = "solicitud_verificacion";

    public function usuarios() {
        return $this->belongsTo('App\Usuarios', 'id_usuario');
    }
}
