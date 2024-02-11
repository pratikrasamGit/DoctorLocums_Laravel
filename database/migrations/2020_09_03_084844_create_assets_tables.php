<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nurse_assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('nurse_id');
            $table->foreign('nurse_id')
                ->references('id')->on('nurses');
            $table->string('name')->nullable();
            $table->string('filter',100)->nullable();
            $table->boolean('active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('facility_assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('facility_id');
            $table->foreign('facility_id')
                ->references('id')->on('facilities');
            $table->string('name')->nullable();
            $table->string('filter',100)->nullable();
            $table->boolean('active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('job_assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('job_id');
            $table->foreign('job_id')
                ->references('id')->on('jobs');
            $table->string('name')->nullable();
            $table->string('filter',100)->nullable();
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('nurse_assets');
        Schema::dropIfExists('facility_assets');
        Schema::dropIfExists('job_assets');
    }
}
