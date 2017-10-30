<?php

use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\App\Usuario::class, function (Faker\Generator $faker) {
//    static $password;

    $sexo = $faker->randomElements([1, 2, 3])[0];
    $nombre = $faker->name;

    if ($sexo === 1) {
        $nombre = $faker->firstNameMale;
    }
    else if ($sexo === 2) {
        $nombre = $faker->firstNameFemale;
    }

    return [
        'nombres' => $nombre,
        'apellidos' => $faker->lastName,
        'id_tipo_identificador' => 2,
        'id_tipo_usuario' => $faker->randomElements([2, 3])[0],
        'identificador' => $faker->unique()->randomNumber(8),
        'email' => $faker->unique()->safeEmail,
        'password' => Hash::make('pato123'),
        'remember_token' => str_random(10),
        'created_at' => time(),
        'updated_at' => time(),
        'id_sexo' => $sexo,
        'fecha_nacimiento' => $faker->date('d-m-Y'),
    ];
});
