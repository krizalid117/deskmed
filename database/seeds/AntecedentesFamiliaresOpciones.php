<?php

use Illuminate\Database\Seeder;

class AntecedentesFamiliaresOpciones extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('antecedentes_familiares_opciones')->insert([
            [ "nombre" => "Tuberculosis", "necesita_especificacion" => false ],
            [ "nombre" => "Diabetes", "necesita_especificacion" => false ],
            [ "nombre" => "Enfermedad al riñón", "necesita_especificacion" => true ],
            [ "nombre" => "Enfermedad al corazón", "necesita_especificacion" => true ],
            [ "nombre" => "Artritis", "necesita_especificacion" => true ],
            [ "nombre" => "Enfermedad al estómago", "necesita_especificacion" => true ],
            [ "nombre" => "Asma", "necesita_especificacion" => false ],
            [ "nombre" => "Epilepsia o convulsiones", "necesita_especificacion" => false ],
            [ "nombre" => "Presión alta", "necesita_especificacion" => false ],
            [ "nombre" => "Infarto", "necesita_especificacion" => false ],
            [ "nombre" => "Migrañas", "necesita_especificacion" => false ],
            [ "nombre" => "Cáncer", "necesita_especificacion" => true ],
            [ "nombre" => "Enfermedad a la sangre", "necesita_especificacion" => true ],
        ]);
    }
}
