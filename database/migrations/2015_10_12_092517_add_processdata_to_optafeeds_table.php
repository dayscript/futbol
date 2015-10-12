<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProcessdataToOptafeedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('optafeeds', function (Blueprint $table) {
            $table->timestamp('processed')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('optafeeds', function (Blueprint $table) {
            $table->dropColumn('processed');
        });
    }
}
