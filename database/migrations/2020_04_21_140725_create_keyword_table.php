<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeywordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keywords', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('created_by');
            $table->foreign('created_by')
                ->references('id')->on('users');
            $table->string('filter')->nullable();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->dateTime('dateTime')->nullable();
            $table->float('amount')->nullable();
            $table->integer('count')->nullable();
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
        Schema::dropIfExists('keywords');
    }
}
