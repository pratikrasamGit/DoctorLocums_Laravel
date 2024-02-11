<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreFieldsToNursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nurses', function (Blueprint $table) {
            $table->boolean('clinical_educator')->default(false);
            $table->boolean('is_daisy_award_winner')->default(false);
            $table->boolean('employee_of_the_mth_qtr_yr')->default(false);
            $table->boolean('other_nursing_awards')->default(false);
            $table->boolean('is_professional_practice_council')->default(false);
            $table->boolean('is_research_publications')->default(false);
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
            $table->dropColumn('clinical_educator');
            $table->dropColumn('is_daisy_award_winner');
            $table->dropColumn('employee_of_the_mth_qtr_yr');
            $table->dropColumn('other_nursing_awards');
            $table->dropColumn('is_professional_practice_council');
            $table->dropColumn('is_research_publications');
        });
    }
}
