<?php

namespace App;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\File;

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

    public function horasAsDoctor() {
        return $this->hasMany('App\HoraMedica', 'id_medico');
    }

    public function horasAsPaciente() {
        return $this->hasMany('App\HoraMedica', 'id_paciente');
    }

    public function getProfileImage() {
        $profilePic = "/profilePics/default_nonbinary.png";

        if (!is_null($this->profile_pic_path) && $this->profile_pic_path !== "" && File::exists(public_path("profilePics/{$this->profile_pic_path}"))) {
            $profilePic = "/profilePics/{$this->profile_pic_path}";
        }
        else {
            if (!is_null($this->id_sexo)) {
                if ($this->id_sexo === 1) { //M
                    $profilePic = "/profilePics/default_male.png";
                }
                else if ($this->id_sexo === 2) { //F
                    $profilePic = "/profilePics/default_female.png";
                }
            }
        }

        return $profilePic;
    }

    public function getProfessionalStatus() {
        $verif = $this->verificaciones()->where('habilitado', true)->first();

        if (!is_null($verif)) {
            return [
                "estado" => 2,
                "estado_img" => "verified",
                "titulo" => $verif->titulo_habilitante_legal,
                "institucion_habilitante" => $verif->institucion_habilitante,
                "especialidad" => $verif->especialidad,
                "nregistro" => $verif->nregistro,
                "fecha_registro" => $verif->fecha_registro,
                "antecedente" => $verif->antecedente_titulo,
            ];
        }
        else {

            $solicitud = $this->solicitudes_verificacion()->where('estado', 0)->first();

            $estado = is_null($solicitud) ? 0 : 1;
            $estadoImg = is_null($solicitud) ? "question" : "waiting";

            return [
                "estado" => $estado,
                "estado_img" => $estadoImg,
                "titulo" => !is_null($this->titulo_segun_usuario) && $this->titulo_segun_usuario !== "" ? $this->titulo_segun_usuario : "Sin especificar",
                "institucion_habilitante" => !is_null($this->institucion_habilitante_segun_usuario) && $this->institucion_habilitante_segun_usuario !== "" ? $this->institucion_habilitante_segun_usuario : "Sin especificar",
                "especialidad" => !is_null($this->especialidad_segun_usuario) && $this->especialidad_segun_usuario !== "" ? $this->especialidad_segun_usuario : "Sin especificar",
                "nregistro" => !is_null($this->nregistro_segun_usuario) && $this->nregistro_segun_usuario !== "" ? $this->nregistro_segun_usuario : "Sin especificar",
                "fecha_registro" => !is_null($this->fecha_registro_segun_usuario) && $this->fecha_registro_segun_usuario !== "" ? $this->fecha_registro_segun_usuario : "Sin especificar",
                "antecedente" => !is_null($this->antecedente_titulo_segun_usuario) && $this->antecedente_titulo_segun_usuario !== "" ? $this->antecedente_titulo_segun_usuario : "Sin especificar",
            ];
        }
    }
}
