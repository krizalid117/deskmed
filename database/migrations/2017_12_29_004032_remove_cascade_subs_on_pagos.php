<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveCascadeSubsOnPagos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropForeign('pagos_id_subscripcion_foreign');
            $table->integer('id_subscripcion')->nullable(true)->change();

            $table->foreign('id_subscripcion')->references('id')->on('subscripciones')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropForeign('pagos_id_subscripcion_foreign');
            $table->integer('id_subscripcion')->nullable(false)->change();

            $table->foreign('id_subscripcion')->references('id')->on('subscripciones')->onUpdate('cascade')->onDelete('cascade');
        });
    }
}
