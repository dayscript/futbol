<?php

namespace Dayscore\Opta;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'opta_venues';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','name'];
}
