<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToHireManagers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hire_managers', function (Blueprint $table) {
            $table->string('hm_position')->nullable();
            $table->string('hm_email')->nullable();
            $table->string('hm_phone',20)->nullable();
            $table->string('hm_mobile',20)->nullable();
            $table->string('hm_address')->nullable();
            $table->string('hm_city',20)->nullable();
            $table->string('hm_state',30)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hire_managers', function (Blueprint $table) {
            $table->dropColumn('hm_position');
            $table->dropColumn('hm_email');
            $table->dropColumn('hm_phone');
            $table->dropColumn('hm_mobile');
            $table->dropColumn('hm_address');
            $table->dropColumn('hm_city');
            $table->dropColumn('hm_state');
        });
    }
}
