<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamePlaytable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('field', function (Blueprint $table) {
            $table->increments('fieldid');
            $table->unsignedInteger('gameid')->nullable();
            $table->unsignedInteger('position1');
            $table->unsignedInteger('position2');
            $table->unsignedInteger('position3');
            $table->unsignedInteger('position4');
            $table->unsignedInteger('position5');
            $table->unsignedInteger('position6');
            $table->unsignedInteger('position7');
            $table->unsignedInteger('position8');
            $table->unsignedInteger('position9');
            /*
            * id's are ordered as numpad
            * 7|8|9
            * 4|5|6
            * 1|2|3
            */

            $table->unsignedInteger('parentid')->nullable();
            $table->unsignedInteger('placement')->nullable();
            $table->unsignedInteger('lastplay')->nullable();

            $table->foreign('parentid')
                ->references('fieldid')->on('field')
                ->onDelete('cascade');
            $table->foreign('gameid')
                ->references('gameid')->on('games')
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
        Schema::dropIfExists('field');
    }
}
