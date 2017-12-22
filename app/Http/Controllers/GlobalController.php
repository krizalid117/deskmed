<?php

namespace App\Http\Controllers;

use \Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Output\ConsoleOutput;
use App\Notifications\VerificationRequestResponded;
use App\Usuario;
use App\Verificaciones;
use App\SolicitudesVerificacion;

class GlobalController
{

    public static function log($msj) {
        $output = new ConsoleOutput();
        $output->writeln("<info>$msj</info>");
    }

    public static function edad($fechaInicio) {
        if (!$fechaInicio || is_null($fechaInicio)) {
            return "Fecha de nacimiento no indicada.";
        }

        $diaActual = date("j");
        $mesActual = date("n");
        $anioActual = date("Y");

        $partes = explode('-', $fechaInicio);

        $diaInicio = $partes[2];
        $mesInicio = $partes[1];
        $anioInicio = $partes[0];

        $b = 0;
        $mes = $mesInicio - 1;

        if ($mes == 2) {
            if (($anioActual % 4 == 0 && $anioActual % 100 != 0) || $anioActual % 400 == 0) {
                $b = 29;
            } else {
                $b = 28;
            }
        } else if ($mes <= 7) {
            if ($mes == 0) {
                $b = 31;
            } else if ($mes % 2 == 0) {
                $b = 30;
            } else {
                $b = 31;
            }
        } else if ($mes > 7) {
            if ($mes % 2 == 0) {
                $b = 31;
            } else {
                $b = 30;
            }
        }
        if ($mesInicio <= $mesActual) {
            $anios = $anioActual - $anioInicio;

            if ($diaInicio <= $diaActual) {
                $meses = $mesActual - $mesInicio;
                $dies = $diaActual - $diaInicio;
            } else {
                if ($mesActual == $mesInicio) {
                    $anios = $anios - 1;
                }

                $meses = ($mesActual - $mesInicio - 1 + 12) % 12;
                $dies = $b - ($diaInicio - $diaActual);
            }
        } else {
            $anios = $anioActual - $anioInicio - 1;

            if ($diaInicio > $diaActual) {
                $meses = $mesActual - $mesInicio - 1 + 12;
                $dies = $b - ($diaInicio - $diaActual);
            } else {
                $meses = $mesActual - $mesInicio + 12;
                $dies = $diaActual - $diaInicio;
            }
        }

        $edadFinal = $anios . " años, " . $meses . " meses y " . $dies . " dias.";
        return $edadFinal;
    }

    public static function edad_anios($fecha) {
        $tz  = new \DateTimeZone('America/Santiago');

        return \DateTime::createFromFormat('Y-m-d', $fecha, $tz)
            ->diff(new \DateTime('now', $tz))
            ->y;
    }

    public function search(Request $request, $keyword) {

//        DB::enableQueryLog();

        DB::listen(function ($sql) {
            GlobalController::log($sql->sql);
        });

        $usuario = Auth::user();

        $consulta = "
            select u.*
            , coalesce(v.titulo_habilitante_legal, nullif(u.titulo_segun_usuario, ''), 'Sin especificar') as titulo
            , coalesce(v.especialidad, nullif(u.especialidad_segun_usuario, ''), 'Sin especificar') as especialidad
            , s.alias_adulto
            , s.alias_infantil
            , case
                when coalesce(v.habilitado, false) is true then
                    'verified'
                when exists(select 1 from solicitud_verificacion sv where sv.id_usuario = u.id and sv.estado = 0) then
                    'waiting'
                else 'question'
            end as icon
            from usuarios u
            join sexos s
              on s.id = u.id_sexo
            left join verificaciones v 
              on v.id_usuario = u.id 
              and v.habilitado is true
            where (
                translate(u.nombres, 'áéíóúÁÉÍÓÚñÑ', 'aeiouAEIOUNn') ilike '%' || translate(:keyword, 'áéíóúÁÉÍÓÚñÑ', 'aeiouAEIOUNn') || '%'
                or translate(u.apellidos, 'áéíóúÁÉÍÓÚñÑ', 'aeiouAEIOUNn') ilike '%' || translate(:keyword, 'áéíóúÁÉÍÓÚñÑ', 'aeiouAEIOUNn') || '%'
                or translate(split_part(u.email, '@', 1), 'áéíóúÁÉÍÓÚñÑ', 'aeiouAEIOUNn') ilike '%' || translate(:keyword, 'áéíóúÁÉÍÓÚñÑ', 'aeiouAEIOUNn') || '%'
                or (
                    v.id is not null and (
                        translate(v.titulo_habilitante_legal, 'áéíóúÁÉÍÓÚñÑ', 'aeiouAEIOUNn') ilike '%' || translate(:keyword, 'áéíóúÁÉÍÓÚñÑ', 'aeiouAEIOUNn') || '%'
                        or translate(v.especialidad, 'áéíóúÁÉÍÓÚñÑ', 'aeiouAEIOUNn') ilike '%' || translate(:keyword, 'áéíóúÁÉÍÓÚñÑ', 'aeiouAEIOUNn') || '%'
                    )
                )
                or (
                    v.id is null and (
                        translate(u.titulo_segun_usuario, 'áéíóúÁÉÍÓÚñÑ', 'aeiouAEIOUNn') ilike '%' || translate(:keyword, 'áéíóúÁÉÍÓÚñÑ', 'aeiouAEIOUNn') || '%'
                        or translate(u.especialidad_segun_usuario, 'áéíóúÁÉÍÓÚñÑ', 'aeiouAEIOUNn') ilike '%' || translate(:keyword, 'áéíóúÁÉÍÓÚñÑ', 'aeiouAEIOUNn') || '%'
                    )
                ) 
                or (
                    u.id_privacidad_identificador = 1
                    and translate(u.identificador, 'áéíóúÁÉÍÓÚñÑ', 'aeiouAEIOUNn') ilike '%' || translate(:keyword, 'áéíóúÁÉÍÓÚñÑ', 'aeiouAEIOUNn') || '%'
                )
            )
            and u.id <> :id
            and u.id_tipo_usuario = :tipo
            
            order by translate(u.nombres, 'áéíóúÁÉÍÓÚñÑ', 'aeiouAEIOUNn'), translate(u.apellidos, 'áéíóúÁÉÍÓÚñÑ', 'aeiouAEIOUNn')
        ";

        $resultsDocs = DB::select($consulta, [ "keyword" => $keyword, "id" => $usuario->id . "", "tipo" => 2 ]); //doctores
        $resultsPat = DB::select($consulta, [ "keyword" => $keyword, "id" => $usuario->id . "", "tipo" => 3 ]); //pacientes

        $results = [
            "d" => [
                "count" => count($resultsDocs),
                "results" => $resultsDocs,
            ],
            "p" => [
                "count" => count($resultsPat),
                "results" => $resultsPat,
            ]
        ];

        return view('search', [
            "usuario" => $usuario,
            "results" => $results,
            "keyword" => $keyword,
//            "log" => DB::getQueryLog(),
        ]);
    }

    public function getNotifications() {
        return view('layouts.partials.all_notifications', [
            "unreadNotifCount" => count(Auth::user()->unreadNotifications),
        ]);
    }

    public function validations() {

        $consulta = "
            select sv.id
            , sv.id_usuario
            , sv.estado
            , sv.comentario
            , to_char(sv.updated_at, 'dd-mm-yyyy HH24:mi:ss') as updated_at
            , cast(extract(epoch from sv.updated_at::timestamp without time zone) as integer) as tstamp
            , concat_ws(' ', u.nombres, u.apellidos) as nombre_completo
            from solicitud_verificacion sv
            join usuarios u
              on u.id = sv.id_usuario
            order by sv.updated_at desc
        ";

        $validations = json_encode(DB::select($consulta));

        return view('admin.validations', [
            "usuario" => Auth::user(),
            "validations" => $validations,
        ]);
    }

    public function subs() {
        $consulta = "
            select s.id
            , s.id_usuario
            , s.id_plan
            , id_pago
            , s.inicio_subscripcion
            , s.termino_subscripcion
            , to_char(s.updated_at, 'dd-mm-yyyy HH24:mi:ss') as updated_at
            , concat_ws(' ', u.nombres, u.apellidos) as usuario_nombre_completo
            , p.nombre as nombre_plan
            , p.precio_mensual::int as precio_mensual_plan
            , pa.estado as estado_pago
            , pa.total::int as total_pago
            from subscripciones s 
            join usuarios u
              on u.id = s.id_usuario
            join plan p
              on p.id = s.id_plan
            join pagos pa
              on pa.id = s.id_pago
        ";
    }

    public function getDoctorInfo(Request $request) {
        $datos = [
            "error" => false,
            "doctor" => [],
        ];

        $consulta = "
            select u.nombres
            , u.apellidos
            , to_char(u.created_at, 'dd-mm-yyyy HH24:mi:ss') as fecha_registro
            , to_char(u.updated_at, 'dd-mm-yyyy HH24:mi:ss') as ultima_actualizacion
            , u.email
            , to_char(u.fecha_nacimiento, 'dd-mm-yyyy') as fecha_nacimiento
            , s.nombre as sexo
            , ti.nombre as tipo_identificador
            , u.identificador
            , coalesce(u.antecedente_titulo_segun_usuario, 'Sin especificar') as antecedente_titulo_segun_usuario
            , coalesce(u.especialidad_segun_usuario, 'Sin especificar') as especialidad_segun_usuario
            , coalesce(u.fecha_registro_segun_usuario, 'Sin especificar') as fecha_registro_segun_usuario
            , coalesce(u.institucion_habilitante_segun_usuario, 'Sin especificar') as institucion_habilitante_segun_usuario
            , coalesce(u.nregistro_segun_usuario, 'Sin especificar') as nregistro_segun_usuario
            , coalesce(u.titulo_segun_usuario, 'Sin especificar') as titulo_segun_usuario
            from usuarios u
            join sexos s
              on s.id = u.id_sexo
            join tipos_identificador ti
              on ti.id = u.id_tipo_identificador
            where u.id = {$request["id"]}
        ";

        if ($r = DB::select($consulta)) {
            $datos["doctor"] = $r[0];
        }
        else {
            $datos["error"] = true;
        }

        return response()->json($datos);
    }

    public function getVerificacionesSolicitud(Request $request) {
        $datos = [
            "error" => false,
            "solicitud" => [],
        ];

        $consulta = "
            select s.id_usuario
            , u.identificador
            , u.id_tipo_identificador
            , s.estado
            , coalesce(s.comentario, '') as comentario
            , to_char(s.created_at, 'dd-mm-yyyy HH24:mi:ss') as fecha_creacion
            , to_char(s.updated_at, 'dd-mm-yyyy HH24:mi:ss') as ultima_actualizacion
            , case
                when count(v) > 0 then
                    json_agg((
                        select to_json(a)
                        from (
                            select v.id,
                            v.habilitado,
                            coalesce(v.titulo_habilitante_legal, '') as titulo,
                            coalesce(v.institucion_habilitante, '') as institucion,
                            coalesce(v.especialidad, '') as especialidad,
                            coalesce(v.nregistro, '') as nregistro,
                            coalesce(v.fecha_registro, '') as fregistro,
                            coalesce(v.antecedente_titulo, '') as antecedente,
                            to_char(v.created_at, 'dd-mm-yyyy HH24:mi:ss') as fecha_creacion,
                            to_char(v.updated_at, 'dd-mm-yyyy HH24:mi:ss') as ultima_actualizacion,
                            concat_ws(' ', uv.nombres, uv.apellidos) as nombre_verificante,
                            uv.id as id_verificante,
                            0 as estado
                        ) a
                    ) order by v.updated_at desc)
                else '[]'
            end as verificaciones
            from solicitud_verificacion s
            join usuarios u
              on u.id = s.id_usuario
            left join verificaciones v
              on s.id = v.id_solicitud
            left join usuarios uv
              on uv.id = v.id_usuario_verificante
            where s.id = {$request["id"]}
            group by s.id, u.id
        ";

        if ($r = DB::select($consulta)) {
            $datos["solicitud"] = $r[0];
        }
        else {
            $datos["error"] = true;
        }

        return response()->json($datos);
    }

    public function verifyExternal(Request $request) {
        $datos = [
            "error" => false,
            "content" => "",
        ];
//
//        $client = new Client();
        $baseUrl = "http://webhosting.superdesalud.gob.cl";
//
//        $res = $client->request('GET', "$baseUrl/prestadoresindividuales.nsf/(searchAll2)/Search?SearchView&Query=(FIELD%20rut_pres={$request["rut"]})&Start=1&count=10");
//
//        var_dump($res->getBody());
//
//        $datos["verificacion"] = $res;

//        return response()->json($datos);

        $step = intval($request["step"]);

        switch ($step) {
            case 1:
                $datos["content"] = (file_get_contents("$baseUrl/bases/prestadoresindividuales.nsf/(searchAll2)/Search?SearchView&Query=(FIELD%20rut_pres={$request["data"]})&Start=1&count=10"));
                break;
            case 2:
                $datos["content"] = utf8_encode(file_get_contents("$baseUrl{$request["data"]}"));
                break;
            case 3:
                $datos["content"] = utf8_encode(file_get_contents("$baseUrl/bases/prestadoresindividuales.nsf/(AntecRegxRut2)/{$request["data"]}?open"));
                break;
        }

        return response()->json($datos);
    }

    public function saveVerification(Request $request) {
        $datos = [
            "error" => false,
            "mensaje" => "",
        ];

        $solicitud = SolicitudesVerificacion::find($request["id_solicitud"]);

        $update = $solicitud->update([
            "estado" => intval($request["estado"]),
            "comentario" => $request["comentario"],
        ]);

        if ($update && $request->exists("verificaciones")) {

            foreach ($request["verificaciones"] as $ver) {
                if (intval($ver["estado"]) === 1) {

                    $verificacion = new Verificaciones();

                    $verificacion->habilitado = $ver["habilitado"] === "true";
                    $verificacion->titulo_habilitante_legal = $ver["titulo"];
                    $verificacion->institucion_habilitante = $ver["institucion"];
                    $verificacion->especialidad = $ver["especialidad"];
                    $verificacion->id_usuario = $solicitud->id_usuario;
                    $verificacion->nregistro = $ver["nregistro"];
                    $verificacion->fecha_registro = $ver["fregistro"];
                    $verificacion->antecedente_titulo = $ver["antecedente"];
                    $verificacion->id_solicitud = $solicitud->id;
                    $verificacion->id_usuario_verificante = Auth::user()->id;

                    $verificacion->save();

                    Usuario::find($solicitud->id_usuario)->notify(new VerificationRequestResponded($solicitud));
                }
                else if (intval($ver["estado"]) === 2) {
                    DB::table('verificaciones')->where('id', $ver["id"])->delete();
                }
            }
        }
        else {
            $datos["error"] = false;
        }

        return response()->json($datos);
    }
}
