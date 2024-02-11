<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLatLangToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->string('f_lat',100)->nullable();
            $table->string('f_lang',100)->nullable();
        });
        Schema::table('nurses', function (Blueprint $table) {
            $table->string('n_lat',100)->nullable();
            $table->string('n_lang',100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropColumn('f_lat');
            $table->dropColumn('f_lang');
        });
        Schema::table('nurses', function (Blueprint $table) {
            $table->dropColumn('n_lat');
            $table->dropColumn('n_lang');
        });
    }
}
