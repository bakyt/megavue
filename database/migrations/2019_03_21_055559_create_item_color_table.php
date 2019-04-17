<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemColorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_color', function (Blueprint $table) {
            $table->integer('item_id')->unsigned();
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('color_id')->unsigned();
            $table->foreign('color_id')->references('id')->on('colors')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_color');
    }
}
