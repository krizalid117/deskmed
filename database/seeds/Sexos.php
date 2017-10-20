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
        ]);

        DB::table('sexos')->insert([
            'nombre' => 'Femenino',
        ]);

        DB::table('sexos')->insert([
            'nombre' => 'No binario',
        ]);
    }
}
