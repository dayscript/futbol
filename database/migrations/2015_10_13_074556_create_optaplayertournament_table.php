<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptaplayertournamentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opta_player_tournament', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('player_id')->unsigned()->nullable();
            $table->integer('team_id')->unsigned()->nullable();
            $table->integer('tournament_id')->unsigned()->nullable();
            $table->date('join_date')->nullable();
            $table->string('jersey_num')->nullable();
            $table->string('position')->nullable();
            $table->string('real_position')->nullable();
            $table->string('real_position_side')->nullable();
            $table->timestamps();

            $table->foreign('player_id')->references('id')->on('opta_players')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('opta_teams')->onDelete('cascade');
            $table->foreign('tournament_id')->references('id')->on('tournaments')->onDelete('cascade');
        });

        Schema::table('opta_players', function (Blueprint $table) {
            $table->dropColumn('jersey_num');
            $table->dropColumn('real_position');
            $table->dropColumn('real_position_side');
            $table->dropColumn('join_date');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('opta_players', function (Blueprint $table) {
            $table->string('jersey_num')->nullable();
            $table->string('real_position')->nullable();
            $table->string('real_position_side')->nullable();
            $table->date('join_date')->nullable();
        });
        Schema::drop('opta_player_tournament');
    }
}
