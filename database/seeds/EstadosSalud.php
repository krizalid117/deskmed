<?php

use Illuminate\Database\Seeder;

class EstadosSalud extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('estados_salud')->insert([
            [ "nombre" => "Excelente" ],
            [ "nombre" => "Bien" ],
            [ "nombre" => "Más o menos" ],
            [ "nombre" => "Mal" ],
            [ "nombre" => "Grave" ],
            [ "nombre" => "Fallecido" ],
        ]);
    }
}
