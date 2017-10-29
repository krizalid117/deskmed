<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsuarioEnfermedadesActuales extends Model
{
    protected $table = "usuario_enfermedades_actuales";

    public function usuario() {
        return $this->belongsTo('App\Usuario', 'id_usuario');
    }

    public function enfermedad() {
        return $this->belongsTo('App\EnfermedadesAntecedentesPersonales', 'id_enfermedad');
    }
}
