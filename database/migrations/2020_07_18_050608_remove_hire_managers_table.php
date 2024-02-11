<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveHireManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('facility_users', function (Blueprint $table) {
            $table->dropForeign('facility_users_hire_manager_id_foreign');
            //$table->dropPrimary('hire_manager_id');
        });
        Schema::table('department_users', function (Blueprint $table) {
            $table->dropForeign('department_users_hire_manager_id_foreign');
            //$table->dropPrimary('hire_manager_id');
        });
        Schema::dropIfExists('hire_managers');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('facility_users', function (Blueprint $table) {
            $table->foreign('hire_manager_id')->references('id')->on('hire_managers');
        });
        Schema::table('department_users', function (Blueprint $table) {
            $table->foreign('hire_manager_id')->references('id')->on('hire_managers');
        });
        Schema::create('hire_managers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->boolean('active')->default(true);
            $table->string('hm_position')->nullable();
            $table->string('hm_email')->nullable();
            $table->string('hm_phone',20)->nullable();
            $table->string('hm_mobile',20)->nullable();
            $table->string('hm_address')->nullable();
            $table->string('hm_city',20)->nullable();
            $table->string('hm_state',30)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }
}
