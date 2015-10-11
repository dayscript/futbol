<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptateamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opta_teams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->timestamps();
        });
        Schema::create('opta_team_tournament', function(Blueprint $table)
        {
            $table->integer('opta_team_id')->unsigned()->index();
            $table->integer('tournament_id')->unsigned()->index();
            $table->timestamps();
            $table->foreign('opta_team_id')->references('id')->on('opta_teams')->onDelete('cascade');
            $table->foreign('tournament_id')->references('id')->on('tournaments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('opta_team_tournament');
        Schema::drop('opta_teams');
    }
}
