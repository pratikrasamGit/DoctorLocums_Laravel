<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNurseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nurses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->foreign('user_id')
                ->references('id')->on('users');
            $table->unsignedBigInteger('specialty');            
            $table->string('nursing_license_state')->nullable();
            $table->string('nursing_license_number',190)->unique()->nullable();
            $table->unsignedBigInteger('highest_nursing_degree')->nullable();            
            $table->boolean('serving_preceptor')->default(false)->nullable();
            $table->boolean('serving_interim_nurse_leader')->default(false)->nullable();
            $table->unsignedBigInteger('leadership_roles')->nullable();
            $table->string('address')->nullable();
            $table->string('city', 50)->nullable();
            $table->enum("state", \App\Enums\State::getKeys())->nullable();
            $table->string('postcode', 15)->nullable();
            $table->string('country', 150)->nullable();
            $table->string('hourly_pay_rate')->nullable();
            $table->decimal('experience_as_acute_care_facility', 6, 2)->nullable();
            $table->decimal('experience_as_ambulatory_care_facility', 6, 2)->nullable();
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
        Schema::dropIfExists('nurses');
    }
}
