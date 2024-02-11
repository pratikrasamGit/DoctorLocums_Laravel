<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('facility_id');
            $table->foreign('facility_id')
                ->references('id')->on('facilities');
            $table->uuid('created_by')->nullable();
            $table->foreign('created_by')
                ->references('id')->on('users');
            $table->string('department_name')->nullable();
            $table->string('department_specialties')->nullable();
            $table->string('department_numbers',20)->nullable();
            $table->string('department_phone',20)->nullable();
            $table->string('department_mobile',20)->nullable();
            $table->boolean('active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('hire_managers', function (Blueprint $table) {
            $table->uuid('department_id')->nullable();
            $table->foreign('department_id')
                ->references('id')->on('departments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('departments');
        Schema::table('hire_managers', function (Blueprint $table) {
            $table->dropForeign('hire_managers_department_id_foreign');
            $table->dropColumn('department_id');
        });
    }
}
