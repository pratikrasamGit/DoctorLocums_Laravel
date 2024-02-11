<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('preferred_experience',10)->nullable()->change();
            $table->unsignedBigInteger('seniority_level')->nullable();
            $table->unsignedBigInteger('job_function')->nullable();
            $table->text('description')->nullable()->change();
            $table->text('responsibilities')->nullable();
            $table->text('qualifications')->nullable();
            $table->unsignedBigInteger('job_cerner_exp')->nullable();
            $table->unsignedBigInteger('job_meditech_exp')->nullable();
            $table->unsignedBigInteger('job_epic_exp')->nullable();
            $table->string('job_other_exp',100)->nullable();
            $table->text('job_photos')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->decimal('preferred_experience', 6, 2)->nullable();
            $table->dropColumn('seniority_level');
            $table->dropColumn('job_function');
            $table->text('description'); 
            $table->dropColumn('responsibilities');       
            $table->dropColumn('qualifications');   
            $table->dropColumn('j_emr_Experience'); 
            $table->dropColumn('job_photos');     
        });
    }
}
