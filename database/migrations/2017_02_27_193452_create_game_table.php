<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->char('player1tag', 1)->default('x');
            $table->unsignedInteger('player2id')->nullable();
            $table->char('player2tag', 1)->nullable();

            $table->integer('gamestate')->default(0);
            $table->integer('gametype'); //To check if normal tic-tac-toe or 'Extreme'

            $table->timestamps();
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
