<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTheFiledForFacilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->string('facility_logo')->nullable();
            $table->string('facility_email')->nullable();
            $table->string('facility_phone',20)->nullable();  
            $table->string('specialty_need')->nullable();  
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
            $table->string('image')->nullable();  
            $table->dropColumn('facility_logo');
            $table->dropColumn('facility_email');
            $table->dropColumn('facility_phone'); 
            $table->dropColumn('specialty_need');   
        });
    }
}
