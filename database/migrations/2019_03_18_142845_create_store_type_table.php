<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_type', function (Blueprint $table) {
            $table->integer('store_id')->unsigned();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('type_id')->unsigned();
            $table->foreign('type_id')->references('id')->on('store_types')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_type');
    }
}
