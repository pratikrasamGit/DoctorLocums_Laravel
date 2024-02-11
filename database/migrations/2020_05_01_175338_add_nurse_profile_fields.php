<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNurseProfileFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nurses', function (Blueprint $table) {
            $table->unsignedBigInteger('ehr_proficiency_cerner')->nullable();
            $table->unsignedBigInteger('ehr_proficiency_meditech')->nullable();
            $table->unsignedBigInteger('ehr_proficiency_epic')->nullable();
            $table->string('ehr_proficiency_other',100)->nullable();
            $table->string('summary')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *∏
     * @return void
     */
    public function down()
    {
        Schema::table('nurses', function (Blueprint $table) {
            $table->dropColumn('ehr_proficiency_cerner');
            $table->dropColumn('ehr_proficiency_meditech');
            $table->dropColumn('ehr_proficiency_epic');
            $table->dropColumn('ehr_proficiency_other');
            $table->dropColumn('summary');
        });
    }
}
