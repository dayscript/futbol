<?php

namespace Dayscore;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Optafeed extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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
    /**
     * The attributes that should be treated as Carbon dates.
     *
     * @var array
     */
    protected $dates = ['created_at','updated_at'];

    /**
     * Format date attribute more human friendly
     *
     * @param $date
     * @return string
     */
    public function getCreatedAtAttribute( $date )
    {
        Carbon::setLocale('es');
        return Carbon::parse($date);
    }

    /**
     * Format date attribute more human friendly
     *
     * @param $date
     * @return string
     */
    public function getUpdatedAtAttribute( $date )
    {
        Carbon::setLocale('es');
        return Carbon::parse($date)->diffForHumans();
    }

    public function tournament()
    {
        if($tournament = Tournament::where('opta_id',$this->competitionId)->where('opta_season',$this->seasonId)->first())
            return $tournament;
        return false;
    }

}
