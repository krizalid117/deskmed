<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TiposIdentificador extends Model
{
    protected $table = "tipos_identificador";

    public function usuarios() {
        return $this->hasMany('App\Usuario');
    }
}
