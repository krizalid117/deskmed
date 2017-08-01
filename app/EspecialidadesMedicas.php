<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EspecialidadesMedicas extends Model
{
    protected $table = "especialidades_medicas";

    public function usuarios() {
        $this->belongsToMany('App\Usuarios', 'usuarios_especialidades', 'especialidad_id', 'usuario_id');
    }
}
