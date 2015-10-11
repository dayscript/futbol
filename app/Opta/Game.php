<?php

namespace Dayscore\Opta;

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
    protected $fillable = ['id','tournament_id'];
}
