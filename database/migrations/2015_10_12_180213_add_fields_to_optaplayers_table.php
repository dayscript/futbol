<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToOptaplayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('opta_players', function (Blueprint $table) {
            $table->string('name')->nullable()->after('id');
            $table->date('birth_date')->nullable();
            $table->string('position')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('first_nationality')->nullable();
            $table->string('preferred_foot')->nullable();
            $table->string('weight')->nullable();
            $table->string('height')->nullable();
            $table->string('jersey_num')->nullable();
            $table->string('real_position')->nullable();
            $table->string('real_position_side')->nullable();
            $table->date('join_date')->nullable();
            $table->string('country')->nullable();
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
            $table->dropColumn('name');
            $table->dropColumn('birth_date');
            $table->dropColumn('position');
            $table->dropColumn('birth_place');
            $table->dropColumn('first_nationality');
            $table->dropColumn('preferred_foot');
            $table->dropColumn('weight');
            $table->dropColumn('height');
            $table->dropColumn('jersey_num');
            $table->dropColumn('real_position');
            $table->dropColumn('real_position_side');
            $table->dropColumn('join_date');
            $table->dropColumn('country');
        });
    }
}
