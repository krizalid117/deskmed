<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUsuarioAntecedentesFamiliares extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuario_antecedentes_familiares', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_usuario');
            $table->integer('id_antecedentes_familiares_opciones');
            $table->string('especificacion')->default('');
            $table->timestamps();

            $table->foreign('id_usuario')->references('id')->on('usuarios')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_antecedentes_familiares_opciones')->references('id')->on('antecedentes_familiares_opciones')->onUpdate('cascade')->onDelete('cascade');
            $table->unique(['id_usuario', 'id_antecedentes_familiares_opciones']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuario_antecedentes_familiares');
    }
}
