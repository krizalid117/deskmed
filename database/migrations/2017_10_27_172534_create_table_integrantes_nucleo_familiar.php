<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableIntegrantesNucleoFamiliar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parentescos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
        });

        Schema::create('estados_salud', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
        });

        Schema::create('integrantes_nucleo_familiar', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_usuario');
            $table->integer('id_parentesco')->nullable();
            $table->integer('edad')->nullable();
            $table->integer('id_estado_salud')->nullable();
            $table->integer('edad_muerte')->nullable();
            $table->string('causa_muerte')->nullable();
            $table->timestamps();

            $table->foreign('id_usuario')->references('id')->on('usuarios')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_parentesco')->references('id')->on('parentescos')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('id_estado_salud')->references('id')->on('estados_salud')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('integrantes_nucleo_familiar');

        Schema::dropIfExists('parentescos');

        Schema::dropIfExists('estados_salud');
    }
}
