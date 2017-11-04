<?php

namespace App;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Notifications\Notifiable;

class Usuario extends Model implements Authenticatable
{
    use \Illuminate\Auth\Authenticatable;
    use Notifiable;

    protected $table = "usuarios";

    public function tipo_usuario()
    {
        return $this->belongsTo('App\tiposUsuario', 'id_tipo_usuario');
    }

    public function tipo_identificador()
    {
        return $this->belongsTo('App\TiposIdentificador', 'id_tipo_identificador');
    }

    public function especialidades()
    {
        return $this->belongsToMany('App\EspecialidadesMedicas', 'usuarios_especialidades', 'usuario_id', 'especialidad_id');
    }

    public function solicitudes_verificacion()
    {
        return $this->hasMany('App\SolicitudesVerificacion', 'id_usuario');
    }

    public function verificaciones()
    {
        return $this->hasMany('App\Verificaciones', 'id_usuario');
    }

    public function sexo()
    {
        return $this->belongsTo('App\Sexos', 'id_sexo');
    }

    public function antecedentesFamiliares()
    {
        return $this->hasMany('App\UsuarioAntecedentesFamiliares', 'id_usuario');
    }

    public function integrantesNucleoFamiliar() {
        return $this->hasMany('App\IntegrantesNucleoFamiliar', 'id_usuario');
    }

    public function enfermedadesActuales() {
        return $this->hasMany('App\UsuarioEnfermedadesActuales', 'id_usuario');
    }

    public function enfermedadesHistoricas() {
        return $this->hasMany('App\UsuarioEnfermedadesHistoricas', 'id_usuario');
    }

    public function doctors() {
        return $this->hasMany('App\UsuarioDoctores', 'id_usuario');
    }
}
