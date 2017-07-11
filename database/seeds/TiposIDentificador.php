<?php

use Illuminate\Database\Seeder;

class TiposIDentificador extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipos_identificador')->insert([
            'id' => 1,
            'nombre' => 'RUT',
        ]);

        DB::table('tipos_identificador')->insert([
            'id' => 2,
            'nombre' => 'Pasaporte',
        ]);
    }
}
