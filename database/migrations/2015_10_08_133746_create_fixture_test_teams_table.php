<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFixtureTestTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fixture_test_teams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('Equipo');
            $table->integer('fixture_test_id')->unsigned();
            $table->integer('team_id')->nullable()->unsigned();
            $table->integer('order')->unsigned();
            $table->timestamps();

            $table->foreign('fixture_test_id')
                ->references('id')
                ->on('fixture_tests')
                ->onDelete('cascade');

            $table->foreign('team_id')
                ->references('id')
                ->on('teams')
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
        Schema::drop('fixture_test_teams');
    }
}
