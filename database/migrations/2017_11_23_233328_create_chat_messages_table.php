<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid_chat_room');
            $table->integer('id_tipo_usuario');
            $table->integer('id_usuario');
            $table->string('message_text')->default('');
            $table->boolean('has_attachment')->default(false);
            $table->string('attachment_file')->nullable();
            $table->timestamps();

            $table->foreign('uuid_chat_room')->references('uuid')->on('chat_rooms')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_tipo_usuario')->references('id')->on('tipos_usuario')->onUpdate('cascade')->onDelete('set null');
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
        Schema::dropIfExists('chat_messages');
    }
}
