<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('created_by')->nullable();
            $table->foreign('created_by')
                ->references('id')->on('users');
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('address')->nullable();
            $table->string('city', 50)->nullable();
            $table->enum("state", \App\Enums\State::getKeys())->nullable();
            $table->string('postcode', 15)->nullable();
            $table->unsignedBigInteger('type');
            $table->boolean('active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facilities');
    }
}
