<?php

use Illuminate\Database\Seeder;

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
    }
}
