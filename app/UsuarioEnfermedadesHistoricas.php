<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsuarioEnfermedadesHistoricas extends Model
{
    protected $table = "usuario_enfermedades_historicas";

    public function usuario() {
        return $this->belongsTo('App\Usuario', 'id_usuario');
    }

    public function enfermedad() {
        return $this->belongsTo('App\EnfermedadesAntecedentesPersonales', 'id_enfermedad');
    }
}
