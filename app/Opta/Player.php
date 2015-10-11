<?php

namespace Dayscore\Opta;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'opta_players';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','first_name','last_name','known'];
}
