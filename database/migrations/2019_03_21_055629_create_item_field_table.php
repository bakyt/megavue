<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemFieldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_field', function (Blueprint $table) {
            $table->integer('item_id')->unsigned();
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('field_id')->unsigned();
            $table->foreign('field_id')->references('id')->on('fields')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_field');
    }
}
