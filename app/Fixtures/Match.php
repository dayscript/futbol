<?php

namespace Dayscore\Fixtures;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fixture_matches';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['home_id','away_id','order','round_id'];

    /**
     * Return Home Team object associated with this Match
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function home()
    {
        return $this->belongsTo('Dayscore\Fixtures\Team','home_id');
    }
    /**
     * Return Away Team object associated with this Match
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function away()
    {
        return $this->belongsTo('Dayscore\Fixtures\Team','away_id');
    }
}
