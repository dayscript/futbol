<?php

namespace Dayscore;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class FixtureTest extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'classicsRound', 'size','user_id'];

    /**
     * The attributes that should be treated as Carbon dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * Format date attribute more human friendly
     *
     * @param $date
     * @return string
     */
    public function getCreatedAtAttribute( $date )
    {
        Carbon::setLocale('es');
        return Carbon::parse($date)->diffForHumans();
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
     * Return all FixtureTestTeam objects associated with this Fixture
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teams()
    {
        return $this->hasMany( 'Dayscore\FixtureTestTeam' )->orderBy('order','asc');
    }

}
