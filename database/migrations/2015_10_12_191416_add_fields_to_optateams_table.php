<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToOptateamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('opta_teams', function (Blueprint $table) {
            $table->string('city')->nullable();
            $table->integer('country_id')->unsigned()->nullable();
            $table->integer('region_id')->unsigned()->nullable();
            $table->string('postal_code')->nullable();
            $table->string('short_name')->nullable();
            $table->string('official_name')->nullable();
            $table->string('nickname')->nullable();
            $table->string('street')->nullable();
            $table->string('web')->nullable();
            $table->string('founded')->nullable();
            $table->string('color1')->nullable();
            $table->string('color2')->nullable();
            $table->string('email')->nullable();
            $table->string('fax')->nullable();
            $table->string('phone')->nullable();
            $table->integer('fifa_rank')->unsigned()->nullable();
            $table->integer('venue_id')->unsigned()->nullable();

            $table->foreign('country_id')
                ->references('id')
                ->on('opta_countries')
                ->onDelete('set null');
            $table->foreign('region_id')
                ->references('id')
                ->on('opta_regions')
                ->onDelete('set null');
            $table->foreign('venue_id')
                ->references('id')
                ->on('opta_venues')
                ->onDelete('set null');

        });
        Schema::table('opta_venues', function (Blueprint $table) {
            $table->integer('capacity')->nullable()->after('city');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('opta_venues', function (Blueprint $table) {
            $table->dropColumn('capacity');
        });
        Schema::table('opta_teams', function (Blueprint $table) {
            $table->dropColumn('city');
            $table->dropColumn('country_id');
            $table->dropColumn('region_id');
            $table->dropColumn('postal_code');
            $table->dropColumn('short_name');
            $table->dropColumn('official_name');
            $table->dropColumn('nickname');
            $table->dropColumn('street');
            $table->dropColumn('web');
            $table->dropColumn('founded');
            $table->dropColumn('color1');
            $table->dropColumn('color2');
            $table->dropColumn('email');
            $table->dropColumn('fax');
            $table->dropColumn('phone');
            $table->dropColumn('fifarank');
            $table->dropColumn('venue_id');
        });
    }
}
