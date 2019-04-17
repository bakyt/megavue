<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemBrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_brand', function (Blueprint $table) {
            $table->integer('item_id')->unsigned();
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('brand_id')->unsigned();
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_brand');
    }
}
