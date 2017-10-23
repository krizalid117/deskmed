<?php

use Illuminate\Database\Seeder;

class PrivacidadOpciones extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('privacidad_opciones')->insert([
            'nombre' => 'Público',
        ]);

        DB::table('privacidad_opciones')->insert([
            'nombre' => 'Contactos',
        ]);

        DB::table('privacidad_opciones')->insert([
            'nombre' => 'Privado',
        ]);
    }
}
