<?php

use Illuminate\Database\Seeder;

class Sexos extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sexos')->insert([
            'nombre' => 'Masculino',
            'alias_adulto' => 'Hombre',
            'alias_infantil' => 'Niño',
        ]);

        DB::table('sexos')->insert([
            'nombre' => 'Femenino',
            'alias_adulto' => 'Mujer',
            'alias_infantil' => 'Niña',
        ]);

        DB::table('sexos')->insert([
            'nombre' => 'No binario',
            'alias_adulto' => 'Indefinido',
            'alias_infantil' => 'Indefinido',
        ]);
    }
}
