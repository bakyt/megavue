<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->unsigned()->nullable()->default(null);
            $table->foreign('item_id')->references('id')->on('items')->onUpdate('cascade')->onDelete('set null');
            $table->integer('store_id')->unsigned()->nullable()->default(null);
            $table->foreign('store_id')->references('id')->on('stores')->onUpdate('cascade')->onDelete('set null');
            $table->integer('user_id')->unsigned()->nullable()->default(null);
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->integer('section_id')->unsigned()->nullable()->default(null);
            $table->foreign('section_id')->references('id')->on('sections')->onUpdate('cascade')->onDelete('set null');
            $table->integer('category_id')->unsigned()->nullable()->default(null);
            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('cascade')->onDelete('set null');
            $table->integer('brand_id')->unsigned()->nullable()->default(null);
            $table->foreign('brand_id')->references('id')->on('brands')->onUpdate('cascade')->onDelete('set null');
            $table->string('title', 512);
            $table->text('images')->nullable()->default(null);
            $table->text('body');
            $table->text('body_fields');
            $table->text('additional_info')->nullable()->default(null);
            $table->boolean('active')->default(true);
            $table->integer('views')->default(0);
            $table->integer('likes')->default(0);
            $table->integer('sold')->default(0);
            $table->integer('price')->default(0);
            $table->integer('sale')->default(0);
            $table->dateTime('sale_expires_in')->nullable()->default(null);
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
        Schema::dropIfExists('products');
    }
}
