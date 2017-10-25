<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSolicitudVerificacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitud_verificacion', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_usuario');
            $table->integer('estado')->default(0)->comment('0: solicitud enviada, 1: solicitud aprobada, 2: solicitud aprobada (no registra), 3: solicitud rechazada (faltan datos)');
            $table->string('comentario')->default('');

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
        Schema::dropIfExists('solicitud_verificacion');
    }
}
