<?php

use Illuminate\Database\Seeder;

class EspecialidadesMedicas extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('especialidades_medicas')->insert([
            'nombre' => 'ANATOMÍA PATOLÓGICA',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'ALERGOLOGIA',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'CARDIOLOGÍA',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'CIRUGÍA CARDIACA',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'CIRUGÍA GENERAL',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'CIRUGÍA PLASTICA',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'CIRUGÍA DE MAMA',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'CIRUGÍA MAXILOFACIAL',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'CIRUGÍA VASCULAR',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'DERMATOLOGÍA',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'ENDOCRINOLOGÍA Y NUTRICIÓN',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'GASTROENTEROLOGÍA',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'GENÉTICA',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'GERIATRÍA',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'GINECOLOGÍA',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'HEMATOLOGÍA',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'HEPATOLOGÍA',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'ENFERMEDADES INFECCIOSAS',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'MEDICINA INTERNA',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'NEFROLOGÍA',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'NEUMOLOGIA',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'NEUROLOGÍA',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'NEUROCIRUGÍA',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'OFTALMOLOGÍA',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'OTORRINOLARINGOLOGIA',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'ONCOLOGÍA',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'PEDIATRÍA',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'PROCTOLOGÍA',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'PSIQUIATRÍA',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'REHABILITACIÓN Y M. DEPORTIVA',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'REUMATOLOGÍA',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'TRAUMATOLOGÍA',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'UROLOGÍA',
        ]);

        DB::table('especialidades_medicas')->insert([
            'nombre' => 'OTRA',
        ]);
    }
}
