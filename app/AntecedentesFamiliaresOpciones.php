<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AntecedentesFamiliaresOpciones extends Model
{
    protected $table = "antecedentes_familiares_opciones";

    public function usuarioAntecedentesFamiliares() {
        return $this->hasMany('App\UsuarioAntecedentesFamiliares', 'id_antecedentes_familiares_opciones');
    }
}
