<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNormalGameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gameplay', function (Blueprint $table) {
            $table->increments('fieldid');
            $table->unsignedInteger('gameid');
            $table->unsignedInteger('parentid')->nullable();
            $table->unsignedInteger('poition1');
            $table->unsignedInteger('poition2');
            $table->unsignedInteger('poition3');
            $table->unsignedInteger('poition4');
            $table->unsignedInteger('poition5');
            $table->unsignedInteger('poition6');
            $table->unsignedInteger('poition7');
            $table->unsignedInteger('poition8');
            $table->unsignedInteger('poition9');
            /**
            * id's are ordered as numpad
            * 7|8|9
            * 4|5|6
            * 1|2|3
            **/

            $table->foreign('parentid')
                ->references('field_id')->on('users')
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
        //
    }
}
