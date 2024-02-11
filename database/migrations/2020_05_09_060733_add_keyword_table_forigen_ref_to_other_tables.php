<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKeywordTableForigenRefToOtherTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nurses', function (Blueprint $table) {
            $table->foreign('ehr_proficiency_cerner')
                ->references('id')->on('keywords');
            $table->foreign('ehr_proficiency_meditech')
                ->references('id')->on('keywords');
            $table->foreign('ehr_proficiency_epic')
                ->references('id')->on('keywords');       
        });
        Schema::table('facilities', function (Blueprint $table) {
            $table->foreign('type')
                ->references('id')->on('keywords');
        });
        Schema::table('experiences', function (Blueprint $table) {
            $table->foreign('facility_type')
                ->references('id')->on('keywords');
        });
        Schema::table('certifications', function (Blueprint $table) {
            $table->foreign('type')
                ->references('id')->on('keywords');
        });
        Schema::table('availability', function (Blueprint $table) {
            $table->foreign('assignment_duration')
                ->references('id')->on('keywords');
            $table->foreign('shift_duration')
                ->references('id')->on('keywords');
            $table->foreign('work_location')
                ->references('id')->on('keywords');  
            $table->foreign('preferred_shift')
                ->references('id')->on('keywords');  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nurses', function (Blueprint $table) {
            $table->dropForeign(['ehr_proficiency']);
            $table->dropForeign(['ehr_proficiency_experience']);
        });
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropForeign(['type']);
        });
        Schema::table('experiences', function (Blueprint $table) {
            $table->dropForeign(['facility_type']);
        });
        Schema::table('certifications', function (Blueprint $table) {
            $table->dropForeign(['type']);
        });
        Schema::table('availability', function (Blueprint $table) {
            $table->dropForeign(['assignment_duration']);
            $table->dropForeign(['shift_duration']);
            $table->dropForeign(['work_location']);
            $table->dropForeign(['preferred_shift']);
        });
    }
}
