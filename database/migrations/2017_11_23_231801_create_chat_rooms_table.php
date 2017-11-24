<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->integer('hora_id');
//            $table->integer('id_medico');
//            $table->integer('id_paciente'); ambos los tengo desde la hora médica
            $table->boolean('activa')->default(false);
            $table->timestamps();

            $table->foreign('hora_id')->references('id')->on('hora_medica')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_rooms');
    }
}
