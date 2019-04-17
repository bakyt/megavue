<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned()->nullable()->default(null);
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('product_id')->unsigned()->nullable()->default(null);
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null')->onUpdate('cascade');
            $table->integer('color_id')->unsigned()->nullable()->default(null);
            $table->foreign('color_id')->references('id')->on('colors')->onDelete('set null')->onUpdate('cascade');
            $table->text('additional')->nullable()->default(null);
            $table->integer('quantity')->default(0);
            $table->integer('total')->default(0);
            $table->integer('sale')->default(0);
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
        Schema::dropIfExists('order_items');
    }
}
