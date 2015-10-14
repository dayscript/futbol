<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRoundsToOptagamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('opta_games', function (Blueprint $table) {
            $table->string('round_type')->nullable();
            $table->integer('round_number')->unsigned()->nullable();
            $table->string('group_name')->nullable();
            $table->integer('next_match')->unsigned()->nullable();
            $table->integer('next_match_loser')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('opta_games', function (Blueprint $table) {
            $table->dropColumn('round_type');
            $table->dropColumn('round_number');
            $table->dropColumn('group_name');
            $table->dropColumn('next_match');
            $table->dropColumn('next_match_loser');
        });
    }
}
