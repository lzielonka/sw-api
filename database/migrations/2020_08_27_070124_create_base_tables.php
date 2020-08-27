<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaseTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('episode', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 191);
        });

        Schema::create('planet', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 191);
        });

        Schema::create('character', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('planet_id', false, true)->nullable();
            $table->string('name', 191);

            $table->foreign('planet_id')->references('id')->on('planet');
        });

        Schema::create('character_friends', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('character_id', false, true)->nullable();
            $table->integer('friend_id', false, true)->nullable();

            $table->foreign('character_id')->references('id')->on('character');
            $table->foreign('friend_id')->references('id')->on('character');
        });

        Schema::create('character_episode', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('character_id', false, true)->nullable();
            $table->integer('episode_id', false, true)->nullable();

            $table->foreign('character_id')->references('id')->on('character');
            $table->foreign('episode_id')->references('id')->on('episode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('character_episodes');
        Schema::dropIfExists('character_friends');
        Schema::dropIfExists('character');
        Schema::dropIfExists('episode');
        Schema::dropIfExists('planet');
    }
}
