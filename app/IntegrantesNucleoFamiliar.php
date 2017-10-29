<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IntegrantesNucleoFamiliar extends Model
{
    protected $table = "integrantes_nucleo_familiar";

    public function usuario() {
        return $this->belongsTo('App\Usuario', 'id_usuario');
    }
}
