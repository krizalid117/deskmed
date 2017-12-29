<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        DB::table('tipos_usuario')->insert([
            'id' => 1,
            'nombre' => 'Administrador'
        ]);

        DB::table('tipos_usuario')->insert([
            'id' => 2,
            'nombre' => 'Doctor'
        ]);

        DB::table('tipos_usuario')->insert([
            'id' => 3,
            'nombre' => 'Paciente'
        ]);

        $this->call(EspecialidadesMedicas::class);
        $this->call(TiposIDentificador::class);
        $this->call(Sexos::class);
        $this->call(PrivacidadOpciones::class);
        $this->call(AntecedentesFamiliaresOpciones::class);
        $this->call(Parentescos::class);
        $this->call(EstadosSalud::class);
        $this->call(Enfermedades::class);

        DB::table('usuarios')->insert([
            [
                "identificador" => "182442054",
                "nombres" => "Patricio Fernando",
                "apellidos" => "Zúñiga González",
                "email" => "patricio.zunigag@gmail.com",
                "password" => Hash::make("pato123"),
                "profile_pic_path" => "363d99027e7ea6c325909b62ce8bf2f6ca673e2783820c79a986a28c2852fb1d616fe98ca84b143340fc8eb37f0b410435cf15633b9f13cc3259cdfe6c1453f2.jpg",
                "fecha_nacimiento" => "1992-07-13",
                "id_tipo_usuario" => 1,
                "id_tipo_identificador" => 1,
                "id_sexo" => 1,
            ],
            [
                "identificador" => "99682620",
                "nombres" => "María",
                "apellidos" => "Bustamante Fernandez",
                "email" => "pacientedemo@gmail.com",
                "password" => Hash::make("pato123"),
                "profile_pic_path" => null,
                "fecha_nacimiento" => "1974-03-26",
                "id_tipo_usuario" => 3,
                "id_tipo_identificador" => 1,
                "id_sexo" => 2,
            ],
        ]);
    }
}
