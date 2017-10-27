<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAliasToSexos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sexos', function (Blueprint $table) {
            $table->string('alias_adulto', 100)->default('');
            $table->string('alias_infantil', 100)->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sexos', function (Blueprint $table) {
            $table->dropColumn('alias_adulto');
            $table->dropColumn('alias_infantil');
        });
    }
}
