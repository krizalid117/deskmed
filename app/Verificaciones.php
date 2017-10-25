<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Verificaciones extends Model
{
    protected $table = "verificaciones";

    public function usuarios() {
        return $this->belongsTo('App\Usuarios', 'id_usuario');
    }
}
