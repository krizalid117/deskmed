<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableHoraMedica extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hora_medica', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_medico');
            $table->integer('id_paciente')->nullable();
            $table->integer('estado')->default(0)->comment = "0: libre - 1: reservada - 2: cancelada";
            $table->string('hex_color')->default('#f1f1f1');
            $table->date('fecha');
            $table->string('hora_inicio', 8);
            $table->string('hora_termino', 8);
            $table->timestamps();

            $table->foreign('id_medico')->references('id')->on('usuarios')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_paciente')->references('id')->on('usuarios')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hora_medica');
    }
}
