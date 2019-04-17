<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id')->unsigned()->nullable()->default(null);
            $table->string('name');
            $table->string('phone_code');
            $table->string('phone');
            $table->date('birth_date')->nullable()->default(null);
            $table->boolean('gender')->nullable()->default(null);
            $table->string('avatar')->nullable()->default(null);
            $table->json('configurations')->nullable()->default(null);
            $table->string('remember_token', 100)->nullable()->default(null);
            $table->string('password')->nullable()->default(null);
            $table->dateTime('visited_at')->nullable()->default(null);
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
        Schema::dropIfExists('users');
    }
}
