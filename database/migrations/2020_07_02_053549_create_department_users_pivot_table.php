<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentUsersPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('department_users', function (Blueprint $table) {
            $table->uuid('department_id');
				$table->foreign('department_id')->references('id')->on('departments');
				$table->uuid('hire_manager_id');
				$table->foreign('hire_manager_id')->references('id')->on('hire_managers');
				$table->primary(['department_id', 'hire_manager_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('department_users');
    }
}
