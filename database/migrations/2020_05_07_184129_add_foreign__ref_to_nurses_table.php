<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignRefToNursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nurses', function (Blueprint $table) {
            $table->foreign('specialty')
                ->references('id')->on('keywords');
            $table->foreign('highest_nursing_degree')
                ->references('id')->on('keywords');
            $table->foreign('leadership_roles')
                ->references('id')->on('keywords');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nurses', function (Blueprint $table) {
            $table->dropForeign(['specialty']);
            $table->dropForeign(['highest_nursing_degree']);
            $table->dropForeign(['leadership_roles']);
        });
    }
}
