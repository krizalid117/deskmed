<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SolicitudesVerificacion extends Model
{
    protected $table = "solicitud_verificacion";

    protected $fillable = ['estado', 'comentario'];

    public function usuario() {
        return $this->belongsTo('App\Usuarios', 'id_usuario');
    }
}
