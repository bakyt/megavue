<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('driver_id')->unsigned()->nullable()->default(null);
            $table->foreign('driver_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->integer('rating');
            $table->string('message', 512)->nullable()->default(null);
            $table->integer('client_id')->unsigned()->nullable()->default(null);
            $table->foreign('client_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
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
        Schema::dropIfExists('feedbacks');
    }
}
