<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGigwageApiRelatedFiledsToNursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nurses', function (Blueprint $table) {
            $table->string('gig_account_id',50)->nullable();
            $table->boolean('is_gig_invite')->default(false); 
            $table->dateTime('gig_account_create_date')->nullable();
            $table->dateTime('gig_account_invite_date')->nullable();
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
            $table->dropColumn('gig_account_id');
            $table->dropColumn('is_gig_invite');
            $table->dropColumn('gig_account_create_date');
            $table->dropColumn('gig_account_invite_date');            
        });
    }
}
