<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fixture_matches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order')->default(0);
            $table->integer('round_id')->nullable()->unsigned();
            $table->integer('home_id')->nullable()->unsigned();
            $table->integer('away_id')->nullable()->unsigned();
            $table->timestamps();

            $table->foreign('round_id')
                ->references('id')
                ->on('fixture_rounds')
                ->onDelete('cascade');
            $table->foreign('home_id')
                ->references('id')
                ->on('fixture_teams')
                ->onDelete('cascade');
            $table->foreign('away_id')
                ->references('id')
                ->on('fixture_teams')
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
        Schema::drop('fixture_matches');
    }
}
