<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveAllHireManagersRefFromHireManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hire_managers', function (Blueprint $table) {
            $table->dropForeign('hire_managers_user_id_foreign');
            $table->dropColumn('user_id');
            $table->dropForeign('hire_managers_created_by_foreign');
            $table->dropColumn('created_by');
            $table->dropForeign('hire_managers_facility_id_foreign');
            $table->dropColumn('facility_id');
            $table->dropForeign('hire_managers_department_id_foreign');
            $table->dropColumn('department_id');
        });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hire_managers', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->foreign('user_id')
                ->references('id')->on('users');
            $table->uuid('created_by');
            $table->foreign('created_by')
                ->references('id')->on('users');
            $table->uuid('facility_id');
            $table->foreign('facility_id')
                ->references('id')->on('facilities');
            $table->uuid('department_id');
            $table->foreign('department_id')
                ->references('id')->on('departments');
        });
    }
}
