<?php

namespace App\Http\Controllers;

use Symfony\Component\Console\Output\ConsoleOutput;

class GlobalController
{

    public static function log($msj) {
        $output = new ConsoleOutput();
        $output->writeln("<info>$msj</info>");
    }

    public static function edad($fechaInicio) {
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
}