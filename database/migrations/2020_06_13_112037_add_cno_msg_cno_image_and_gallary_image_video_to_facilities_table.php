<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCnoMsgCnoImageAndGallaryImageVideoToFacilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->text('cno_message')->nullable();
            $table->string('cno_image')->nullable();
            $table->string('gallary_images')->nullable();
            $table->string('video')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('instagram')->nullable();
            $table->string('pinterest')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('sanpchat')->nullable();
            $table->string('youtube')->nullable();
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
            $table->dropColumn('cno_message');
            $table->dropColumn('cno_image');
            $table->dropColumn('gallary_images');
            $table->dropColumn('video');
            $table->dropColumn('facebook');
            $table->dropColumn('twitter');
            $table->dropColumn('linkedin');
            $table->dropColumn('instagram');
            $table->dropColumn('pinterest');
            $table->dropColumn('tiktok');
            $table->dropColumn('sanpchat');
            $table->dropColumn('youtube');
        });
    }
}
