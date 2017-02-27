<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->increments('gameid');
            $table->unsignedInteger('player1id');
            $table->unsignedInteger('player2id');

            $table->integer('gamestate');
            $table->integer('gametype'); //To check if normal tic-tac-toe or 'Extreme'
            
            $table->dateTime('created_at');
            $table->dateTime('finished_at')->nullable();

            $table->foreign('player1id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('player2id')
                ->references('id')->on('users')
                ->onDelete('cascade');



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
