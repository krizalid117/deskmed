<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdentificadorToUsuarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->string('identificador', 100);
            $table->integer('id_tipo_identificador');

            $table->foreign('id_tipo_identificador')->references('id')->on('tipos_identificador')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('usuarios', 'identificador')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->dropColumn('identificador');
            });
        }

        if (Schema::hasColumn('usuarios', 'id_tipo_identificador')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->dropColumn('id_tipo_identificador');
            });
        }
    }
}
