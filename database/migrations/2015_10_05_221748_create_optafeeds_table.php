<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptafeedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('optafeeds', function (Blueprint $table) {
            $table->increments('id');
            $table->string('feedType');
            $table->string('feedParameters');
            $table->string('defaultFilename');
            $table->string('deliveryType');
            $table->longText('messageMD5');
            $table->string('competitionId');
            $table->string('seasonId');
            $table->string('gameId');
            $table->string('gameSystemId');
            $table->string('matchday');
            $table->string('awayTeamId');
            $table->string('homeTeamId');
            $table->string('gameStatus');
            $table->string('language');
            $table->string('productionServer');
            $table->string('productionServerTimeStamp');
            $table->string('productionServerModule');
            $table->string('mimeType');
            $table->string('encoding');
            $table->string('sportId');
            $table->string('contentLength');
            $table->string('metaId');
            $table->string('feedId');
            $table->string('dateCreated');
            $table->longText('messageDigest');
            $table->longText('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('optafeeds');
    }
}
