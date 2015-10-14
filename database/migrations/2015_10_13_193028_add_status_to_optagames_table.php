<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToOptagamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('opta_games', function (Blueprint $table) {
            $table->string('status')->default("PRE-MATCH");
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
            $table->dropColumn('status');
        });
    }
}
