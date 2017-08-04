<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('identificador', 50);
            $table->string('nombres', 100)->default('');
            $table->string('apellidos', 100)->default('');
            $table->string('name', 50)->nullable(); //campo para el Auth
            $table->string('email', 255);
            $table->string('password');
            $table->date('fecha_nacimiento')->nullable();
            $table->integer('id_tipo_usuario')->nullable();
            $table->integer('id_tipo_identificador');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('id_tipo_usuario')->references('id')->on('tipos_usuario')->onDelete('set null');
            $table->foreign('id_tipo_identificador')->references('id')->on('tipos_identificador')->onDelete('restrict');
            $table->unique(['id_tipo_identificador', 'identificador']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios_especialidades');
        Schema::dropIfExists('usuarios');
    }
}
