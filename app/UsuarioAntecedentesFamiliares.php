<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsuarioAntecedentesFamiliares extends Model
{
    protected $table = "usuario_antecedentes_familiares";

    public function usuario() {
        return $this->belongsTo('App\Usuario', 'id_usuario');
    }

    public function antecedentesFamiliaresOpcion() {
        return $this->belongsTo('App\AntecedentesFamiliaresOpciones', 'id_antecedentes_familiares_opciones');
    }
}
