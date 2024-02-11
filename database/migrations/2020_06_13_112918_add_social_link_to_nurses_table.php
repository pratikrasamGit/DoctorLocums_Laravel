<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSocialLinkToNursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nurses', function (Blueprint $table) {
            $table->string('nurses_video')->nullable();
            $table->string('nurses_facebook')->nullable();
            $table->string('nurses_twitter')->nullable();
            $table->string('nurses_linkedin')->nullable();
            $table->string('nurses_instagram')->nullable();
            $table->string('nurses_pinterest')->nullable();
            $table->string('nurses_tiktok')->nullable();
            $table->string('nurses_sanpchat')->nullable();
            $table->string('nurses_youtube')->nullable();
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
            $table->dropColumn('nurses_video');
            $table->dropColumn('nurses_facebook');
            $table->dropColumn('nurses_twitter');
            $table->dropColumn('nurses_linkedin');
            $table->dropColumn('nurses_instagram');
            $table->dropColumn('nurses_pinterest');
            $table->dropColumn('nurses_tiktok');
            $table->dropColumn('nurses_sanpchat');
            $table->dropColumn('nurses_youtube');
        });
    }
}
