<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToNursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nurses', function (Blueprint $table) {
            $table->text('additional_photos')->nullable()->change();
            $table->text('additional_files')->nullable();
            $table->string('college_uni_name')->nullable();
            $table->string('college_uni_city', 50)->nullable();
            $table->enum("college_uni_state", \App\Enums\State::getKeys())->nullable();
            $table->string('college_uni_country', 150)->nullable();
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
            $table->dropColumn('additional_files');
            $table->dropColumn('college_uni_name');
            $table->dropColumn('college_uni_city');
            $table->dropColumn('college_uni_state');
            $table->dropColumn('college_uni_country');
        });
    }
}
