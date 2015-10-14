<?php

namespace Dayscore\Opta;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'opta_games';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','tournament_id','status','period','date',
        'timezone',
        'match_type',
        'match_day',
        'match_winner',
        'venue_id',
        'city',
        'home_team',
        'home_halfscore',
        'home_score',
        'away_team',
        'away_halfscore',
        'away_score'
    ];

    public function getDateAttribute( $date )
    {
        if($date){
            if($this->timezone == "BST")
                $result = Carbon::createFromFormat("Y-m-d H:i:s", $date, "Europe/London");
            else if($this->timezone == "GMT")
                $result = Carbon::createFromFormat("Y-m-d H:i:s", $date, "UTC");
            else
                $result = Carbon::createFromFormat("Y-m-d H:i:s", $date, "America/Bogota");
            $result->timezone = 'America/Bogota';
            return $result->format("Y-m-d H:i");

        } else {
            return "";
        }
    }
    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function venue()
    {
        return $this->belongsTo( 'Dayscore\Opta\Venue' );
    }
    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function home()
    {
        return $this->belongsTo( 'Dayscore\Opta\Team', 'home_team' );
    }
    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function away()
    {
        return $this->belongsTo( 'Dayscore\Opta\Team', 'away_team' );
    }

    /**
     * Get the events associated with this game
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany( 'Dayscore\Opta\Event')->orderBy('minute')->orderBy('second');
    }
    /**
     * Tournament of this game
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tournament()
    {
        return $this->belongsTo( 'Dayscore\Tournament');
    }

}
