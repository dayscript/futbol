<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptagamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opta_games', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tournament_id')->unsigned()->nullable();
            $table->dateTime('date')->nullable();
            $table->string('timezone')->nullable();
            $table->string('match_type')->nullable();
            $table->integer('match_day')->nullable();
            $table->integer('match_winner')->nullable()->unsigned();
            $table->integer('venue_id')->nullable()->unsigned();
            $table->string('period')->nullable();
            $table->string('city')->nullable();
            $table->integer('home_team')->nullable()->unsigned();
            $table->integer('home_halfscore')->unsigned()->default(0);
            $table->integer('home_score')->unsigned()->default(0);
            $table->integer('away_team')->nullable()->unsigned();
            $table->integer('away_halfscore')->unsigned()->default(0);
            $table->integer('away_score')->unsigned()->default(0);
            $table->timestamps();

            $table->foreign('tournament_id')
                ->references('id')
                ->on('tournaments')
                ->onDelete('cascade');
            $table->foreign('match_winner')
                ->references('id')
                ->on('opta_teams')
                ->onDelete('cascade');
            $table->foreign('venue_id')
                ->references('id')
                ->on('opta_venues')
                ->onDelete('set null');
            $table->foreign('home_team')
                ->references('id')
                ->on('opta_teams')
                ->onDelete('cascade');
            $table->foreign('away_team')
                ->references('id')
                ->on('opta_teams')
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
        Schema::drop('opta_games');
    }
}
