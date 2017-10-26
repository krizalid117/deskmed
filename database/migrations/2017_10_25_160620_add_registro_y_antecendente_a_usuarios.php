<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRegistroYAntecendenteAUsuarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->string('nregistro_segun_usuario', 100)->nullable();
            $table->string('fecha_registro_segun_usuario', 10)->nullable();
            $table->string('antecedente_titulo_segun_usuario', 255)->nullable();
        });

        Schema::table('verificaciones', function (Blueprint $table) {
            $table->string('nregistro', 100)->default('');
            $table->string('fecha_registro', 10)->default('');
            $table->string('antecedente_titulo', 255)->default('');
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
            $table->dropColumn('nregistro_segun_usuario');
            $table->dropColumn('fecha_registro_segun_usuario');
            $table->dropColumn('antecedente_titulo_segun_usuario');
        });

        Schema::table('verificaciones', function (Blueprint $table) {
            $table->dropColumn('nregistro');
            $table->dropColumn('fecha_registro');
            $table->dropColumn('antecedente_titulo');
        });
    }
}
