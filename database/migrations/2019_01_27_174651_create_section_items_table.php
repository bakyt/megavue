<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('section_id')->nullable()->unsigned()->default(null);
            $table->foreign('section_id')->references('id')->on('sections')->onUpdate('cascade')->onDelete('set null');
            $table->string('name');
            $table->string('route');
            $table->string('icon')->nullable()->default(null);
            $table->boolean('hidden')->default(false);
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
        Schema::dropIfExists('section_items');
    }
}
