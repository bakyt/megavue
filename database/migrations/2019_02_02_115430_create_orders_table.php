<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('from_geo');
            $table->string('to_geo');
            $table->integer('client_id')->unsigned()->nullable()->default(null);
            $table->foreign('client_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->integer('transport_id')->unsigned()->nullable()->default(null);
            $table->foreign('transport_id')->references('id')->on('transports')->onUpdate('cascade')->onDelete('set null');
            $table->integer('region_id')->unsigned()->nullable()->default(null);
            $table->foreign('region_id')->references('id')->on('regions')->onUpdate('cascade')->onDelete('set null');
            $table->integer('price');
            $table->integer('transport_type_id')->unsigned()->nullable()->default(null);
            $table->foreign('transport_type_id')->references('id')->on('transport_types')->onUpdate('cascade')->onDelete('set null');
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
        Schema::dropIfExists('orders');
    }
}
