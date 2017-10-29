<?php

use Illuminate\Database\Seeder;

class Enfermedades extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('enfermedades_antecedentes_personales')->insert([
            [ "nombre" => "Condición suprarrenal" ],
            [ "nombre" => "Vértigo" ],
            [ "nombre" => "Anemia" ],
            [ "nombre" => "Ansiedad" ],
            [ "nombre" => "Asma" ],
            [ "nombre" => "Problemas a la espalda" ],
            [ "nombre" => "Problemas al cuello" ],
            [ "nombre" => "Infección de vejiga" ],
            [ "nombre" => "Cáncer/tumor/leucemia" ],
            [ "nombre" => "Dolor abdominal" ],
            [ "nombre" => "Varicela" ],
            [ "nombre" => "Sordera/pérdida de la audición" ],
            [ "nombre" => "Depresión" ],
            [ "nombre" => "Diabetes" ],
            [ "nombre" => "Problemas digestivos" ],
            [ "nombre" => "Mareos" ],
            [ "nombre" => "Dependencia a drogas o alcohol" ],
            [ "nombre" => "Desorden alimenticio" ],
            [ "nombre" => "Desmayos" ],
            [ "nombre" => "Congelamiento (lesiones por frío extremo)" ],
            [ "nombre" => "Lesión/conmoción cerebral" ],
            [ "nombre" => "Enfermedad al corazón" ],
            [ "nombre" => "Soplo del corazón" ],
            [ "nombre" => "Problemas con el ritmo cardíaco" ],
            [ "nombre" => "Hepatitis" ],
            [ "nombre" => "Hernias" ],
            [ "nombre" => "Presión alta" ],
            [ "nombre" => "VIH/SIDA" ],
            [ "nombre" => "Hiperventilación" ],
            [ "nombre" => "Hipotermia" ],
            [ "nombre" => "Insomnio" ],
            [ "nombre" => "Enfermedades a las articulaciones/huesos" ],
            [ "nombre" => "Enfermedad a los riñones/cálculos renales" ],
            [ "nombre" => "Malaria" ],
            [ "nombre" => "Sarampión" ],
            [ "nombre" => "Migrañas" ],
            [ "nombre" => "Mononucleosis" ],
            [ "nombre" => "Paperas" ],
            [ "nombre" => "Flebitis" ],
            [ "nombre" => "Polio" ],
            [ "nombre" => "Fiebre reumática" ],
            [ "nombre" => "Rubeóla" ],
            [ "nombre" => "Epilepsia/convulsiones" ],
            [ "nombre" => "Anemia falciforme" ],
            [ "nombre" => "Problemas a la piel" ],
            [ "nombre" => "Úlcera estomacal" ],
            [ "nombre" => "Infarto" ],
            [ "nombre" => "Intento de suicidio" ],
            [ "nombre" => "Sensibilidad al sol" ],
            [ "nombre" => "Insolación" ],
            [ "nombre" => "Condición de la tiroides" ],
            [ "nombre" => "Amigdalectomía" ],
            [ "nombre" => "Tuberculosis" ],
        ]);
    }
}
