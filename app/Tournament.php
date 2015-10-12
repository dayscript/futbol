<?php

namespace Dayscore;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','name','opta_id','opta_season'];

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

    /**
     * Get the opta-games associated with this tournament
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function optagames()
    {
        return $this->hasMany( 'Dayscore\Opta\Game' )->orderBy('date','desc');
    }

    /**
     * Get the opta-teams associated with this tournamet
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function optateams()
    {
        return $this->belongsToMany('Dayscore\Opta\Team','opta_team_tournament','tournament_id','opta_team_id')->withTimestamps();
    }

}
