<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptaeventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opta_events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->nullable();
            $table->integer('game_id')->unsigned()->nullable();
            $table->integer('minute')->unsigned()->default(0);
            $table->integer('second')->unsigned()->default(0);
            $table->string('time')->default(0);
            $table->dateTime('datetime')->nullable();
            $table->string('period')->nullable();
            $table->string('goal_type')->nullable();
            $table->string('red_card_type')->nullable();
            $table->integer('player_id')->unsigned()->nullable();
            $table->integer('team_id')->unsigned()->nullable();
            $table->string('sub_reason')->nullable();
            $table->integer('sub_on_player_id')->unsigned()->nullable();
            $table->text('comment')->nullable();
            $table->string('comment_type')->nullable();
            $table->integer('comment_player_ref2')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('game_id')
                ->references('id')
                ->on('opta_games')
                ->onDelete('cascade');
            $table->foreign('player_id')
                ->references('id')
                ->on('opta_players')
                ->onDelete('set null');
            $table->foreign('team_id')
                ->references('id')
                ->on('opta_teams')
                ->onDelete('set null');
            $table->foreign('sub_on_player_id')
                ->references('id')
                ->on('opta_players')
                ->onDelete('set null');
            $table->foreign('comment_player_ref2')
                ->references('id')
                ->on('opta_players')
                ->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('opta_events');
    }
}
