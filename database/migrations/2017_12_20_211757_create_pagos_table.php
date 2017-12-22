<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_usuario');
            $table->integer('estado')->default(0); //0 = normal, 1 = eliminado
            $table->double('total')->default(0);
            $table->timestamps();

            $table->foreign('id_usuario')->references('id')->on('usuarios')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('subscripcion_pagos', function (Blueprint $table) {
            $table->integer('id_pago');
            $table->integer('id_subscripcion');
            $table->timestamps();

            $table->foreign('id_pago')->references('id')->on('pagos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_subscripcion')->references('id')->on('subscripciones')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscripcion_pagos');
        Schema::dropIfExists('pagos');
    }
}
