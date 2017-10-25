<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableVerificaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verificaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('habilitado')->default(false);
            $table->string('titulo_habilitante_legal', 255)->default('');
            $table->string('institucion_habilitante', 255)->default('');
            $table->string('especialidad', 255)->default('');
            $table->integer('id_usuario');
            $table->timestamps();

            $table->foreign('id_usuario')->references('id')->on('usuarios')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('verificaciones');
    }
}
