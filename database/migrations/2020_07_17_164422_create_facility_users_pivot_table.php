<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacilityUsersPivotTable extends Migration
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
				$table->uuid('hire_manager_id');
				$table->foreign('hire_manager_id')->references('id')->on('hire_managers');
				$table->primary(['facility_id', 'hire_manager_id']);
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
    }
}
