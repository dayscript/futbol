<?php

namespace Dayscore\Fixtures;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Fixture extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fixtures';
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
     * Return all Team objects associated with this Fixture
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teams()
    {
        return $this->hasMany( 'Dayscore\Fixtures\Team' )->orderBy('order','asc');
    }

}
