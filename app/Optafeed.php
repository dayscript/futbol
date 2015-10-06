<?php

namespace Dayscore;

use Illuminate\Database\Eloquent\Model;

class Optafeed extends Model
{
    protected $fillable = [
        'feedType',
        'feedParameters',
        'defaultFilename',
        'deliveryType',
        'messageMD5',
        'competitionId',
        'seasonId',
        'gameId',
        'gameSystemId',
        'matchday',
        'awayTeamId',
        'homeTeamId',
        'gameStatus',
        'language',
        'productionServer',
        'productionServerTimeStamp',
        'productionServerModule',
        'mimeType',
        'encoding',
        'sportId',
        'contentLength',
        'metaId',
        'feedId',
        'dateCreated',
        'messageDigest',
        'content'
    ];
}
