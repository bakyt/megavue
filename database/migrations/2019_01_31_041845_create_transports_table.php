<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type_id')->unsigned()->nullable()->default(null);
            $table->foreign('type_id')->references('id')->on('transport_types')->onUpdate('cascade')->onDelete('set null');
            $table->integer('user_id')->unsigned()->nullable()->default(null);
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->integer('brand_id')->unsigned()->nullable()->default(null);
            $table->foreign('brand_id')->references('id')->on('brands')->onUpdate('cascade')->onDelete('set null');
            $table->integer('model_id')->unsigned()->nullable()->default(null);
            $table->foreign('model_id')->references('id')->on('brand_models')->onUpdate('cascade')->onDelete('set null');
            $table->integer('color_id')->unsigned()->nullable()->default(null);
            $table->foreign('color_id')->references('id')->on('colors')->onUpdate('cascade')->onDelete('set null');
            $table->string('number')->nullable()->default(null);
            $table->string('image')->nullable()->default(null);
            $table->text('fields')->nullable()->default(null);
            $table->text('field_ids')->nullable()->default(null);
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
        Schema::dropIfExists('transports');
    }
}
