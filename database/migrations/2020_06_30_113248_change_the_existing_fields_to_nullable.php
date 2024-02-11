<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTheExistingFieldsToNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->string('organization_name')->nullable()->change();
            $table->string('organization_department_name')->nullable()->change();
            $table->string('position_title',100)->nullable()->change();
            $table->string('exp_city',50)->nullable()->change();
            $table->date('start_date')->nullable()->change();
            $table->dropColumn('exp_state');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('experiences', function (Blueprint $table) {
            //
        });
    }
}
