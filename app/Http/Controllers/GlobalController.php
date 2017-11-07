<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Output\ConsoleOutput;
use \App\Verificaciones;

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

//        dd($validations);

        return view('admin.validations', [
            "usuario" => Auth::user(),
            "validations" => $validations,
        ]);
    }
}