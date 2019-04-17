<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable()->default(null);
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('background_image')->nullable()->default(null);
            $table->string('icon')->nullable()->default(null);
            $table->text('about')->nullable()->default(null);
            $table->text('contacts')->nullable()->default(null);
            $table->string('delivery')->nullable()->default(null);
            $table->text('categories')->nullable()->default(null);
            $table->text('config')->nullable()->default(null);
            $table->string('sale_categories')->nullable()->default(null);
            $table->dateTime('sale_expires_in')->nullable()->default(null);
            $table->float('rating')->default(0);
            $table->string('address')->nullable()->default(null);
            $table->string('address_map')->nullable()->default(null);
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
        Schema::dropIfExists('stores');
    }
}
