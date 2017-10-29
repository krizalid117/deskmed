<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEnfermedadesAntecedentesPersonales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enfermedades_antecedentes_personales', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 255);
        });

        Schema::create('usuario_enfermedades_actuales', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_usuario');
            $table->integer('id_enfermedad');

            $table->unique(['id_usuario', 'id_enfermedad']);

            $table->foreign('id_usuario')->references('id')->on('usuarios')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_enfermedad')->references('id')->on('enfermedades_antecedentes_personales')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('usuario_enfermedades_historicas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_usuario');
            $table->integer('id_enfermedad');

            $table->unique(['id_usuario', 'id_enfermedad']);

            $table->foreign('id_usuario')->references('id')->on('usuarios')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_enfermedad')->references('id')->on('enfermedades_antecedentes_personales')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuario_enfermedades_actuales');
        Schema::dropIfExists('usuario_enfermedades_historicas');
        Schema::dropIfExists('enfermedades_antecedentes_personales');
    }
}
