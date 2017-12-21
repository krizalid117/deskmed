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

        Schema::table('subscripciones', function (Blueprint $table) {
            $table->integer('id_pago')->nullable();

            $table->foreign('id_pago')->references('id')->on('pagos')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscripciones', function (Blueprint $table) {
            $table->dropColumn('id_pago');
        });

        Schema::dropIfExists('pagos');
    }
}
