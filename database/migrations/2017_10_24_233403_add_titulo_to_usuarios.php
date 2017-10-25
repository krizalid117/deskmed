<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTituloToUsuarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->string('titulo_segun_usuario', 255)->nullable();
            $table->string('institucion_habilitante_segun_usuario', 255)->nullable();
            $table->string('especialidad_segun_usuario', 255)->nullable();
            $table->boolean('autenticidad_profesional_verificada')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn('titulo_segun_usuario');
            $table->dropColumn('institucion_habilitante_segun_usuario');
            $table->dropColumn('especialidad_segun_usuario');
            $table->dropColumn('autenticidad_profesional_verificada');
        });
    }
}
