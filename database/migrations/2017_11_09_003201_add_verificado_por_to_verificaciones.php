<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVerificadoPorToVerificaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('verificaciones', function (Blueprint $table) {
            $table->integer('id_solicitud')->nullable();
            $table->integer('id_usuario_verificante')->nullable();
            $table->foreign('id_solicitud')->references('id')->on('solicitud_verificacion')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('id_usuario_verificante')->references('id')->on('usuarios')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('verificaciones', function (Blueprint $table) {
            $table->dropColumn('id_solicitud');
            $table->dropColumn('id_usuario_verificante');
        });
    }
}
