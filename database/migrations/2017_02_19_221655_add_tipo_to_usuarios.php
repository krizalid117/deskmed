<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTipoToUsuarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->integer('id_tipo_usuario')->nullable();

            $table->foreign('id_tipo_usuario')->references('id')->on('tipos_usuario')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('usuarios', 'id_tipo_usuario')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->dropColumn('id_tipo_usuario');
            });
        }
    }
}
