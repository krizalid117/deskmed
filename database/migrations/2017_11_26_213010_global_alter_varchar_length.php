<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class GlobalAlterVarcharLength extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('verificaciones', function (Blueprint $table) {
            $table->string('antecedente_titulo', 1000)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::table('verificaciones', function (Blueprint $table) {
          //  $table->string('antecedente_titulo', 255)->change();
        //});
    }
}
