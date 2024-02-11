<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('hire_manager_id');
            $table->foreign('hire_manager_id')
                ->references('id')->on('hire_managers');
            $table->unsignedBigInteger('preferred_specialty');   
            $table->unsignedBigInteger('preferred_assignment_duration')->nullable();
            $table->unsignedBigInteger('preferred_shift_duration')->nullable();
            $table->unsignedBigInteger('preferred_work_location')->nullable();
            $table->unsignedBigInteger('preferred_work_area')->nullable();
            $table->string("preferred_days_of_the_week")->nullable();
            $table->string('preferred_hourly_pay_rate',4)->nullable(); 
            $table->decimal('preferred_experience', 6, 2)->nullable();
            $table->text('description');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
