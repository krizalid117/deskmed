<?php

use Illuminate\Database\Seeder;

class Parentescos extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('parentescos')->insert([
            [ "nombre" => "Hermano" ],
            [ "nombre" => "Hermana" ],
            [ "nombre" => "Padre" ],
            [ "nombre" => "Madre" ],
            [ "nombre" => "Abuelo" ],
            [ "nombre" => "Abuela" ],
            [ "nombre" => "Tío" ],
            [ "nombre" => "Tía" ],
            [ "nombre" => "Hijo" ],
            [ "nombre" => "Hija" ],
            [ "nombre" => "Primo" ],
            [ "nombre" => "Prima" ],
        ]);
    }
}
