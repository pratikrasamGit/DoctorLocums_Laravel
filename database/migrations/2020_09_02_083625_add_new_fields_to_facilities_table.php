<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToFacilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->string('f_emr',150)->nullable();
            $table->string('f_emr_other',150)->nullable();
            $table->string('f_bcheck_provider',150)->nullable();
            $table->string('f_bcheck_provider_other',150)->nullable();
            $table->string('nurse_cred_soft',150)->nullable();
            $table->string('nurse_cred_soft_other',150)->nullable();
            $table->string('nurse_scheduling_sys',150)->nullable();
            $table->string('nurse_scheduling_sys_other',150)->nullable();
            $table->string('time_attend_sys',150)->nullable();
            $table->string('time_attend_sys_other',150)->nullable();
            $table->string('licensed_beds',50)->nullable();
            $table->string('trauma_designation',150)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropColumn('f_emr');
            $table->dropColumn('f_emr_other');
            $table->dropColumn('f_bcheck_provider');
            $table->dropColumn('f_bcheck_provider_other');
            $table->dropColumn('nurse_cred_soft');
            $table->dropColumn('nurse_cred_soft_other');
            $table->dropColumn('nurse_scheduling_sys');
            $table->dropColumn('nurse_scheduling_sys_other');
            $table->dropColumn('time_attend_sys');
            $table->dropColumn('time_attend_sys_other');
            $table->dropColumn('licensed_beds');
            $table->dropColumn('trauma_designation');
        });
    }
}
