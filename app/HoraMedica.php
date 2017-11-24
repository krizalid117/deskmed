<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HoraMedica extends Model
{
    protected $table = "hora_medica";

    public function medico() {
        return $this->belongsTo('App\Usuario', 'id_medico');
    }

    public function paciente() {
        return $this->belongsTo('App\Usuario', 'id_paciente');
    }

    public function chatRoom() {
        return $this->belongsTo('App\ChatRoom');
    }
}
