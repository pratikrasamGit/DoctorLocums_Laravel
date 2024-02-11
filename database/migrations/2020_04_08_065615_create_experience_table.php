<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExperienceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experiences', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('nurse_id');
            $table->foreign('nurse_id')
                ->references('id')->on('nurses');
            $table->string('organization_name');
            $table->string('organization_department_name');
            $table->string('position_title', 100);
            $table->string('exp_city', 50);
            $table->enum("exp_state", \App\Enums\State::getKeys());
            $table->unsignedBigInteger('facility_type')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->text('linkedin_link')->nullable();
            $table->text('description_job_duties')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('experiences');
    }
}
