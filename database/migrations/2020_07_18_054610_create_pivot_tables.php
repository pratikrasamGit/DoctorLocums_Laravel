<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePivotTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facility_users', function (Blueprint $table) {
            $table->uuid('facility_id');
            $table->foreign('facility_id')->references('id')->on('facilities');
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->primary(['facility_id', 'user_id']);
        });

        Schema::create('department_users', function (Blueprint $table) {
            $table->uuid('department_id');
            $table->foreign('department_id')->references('id')->on('departments');
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->primary(['department_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facility_users');
        Schema::dropIfExists('department_users');        
    }
}
