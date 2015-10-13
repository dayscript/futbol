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
    protected $fillable = ['id',
        'first_name',
        'last_name',
        'name',
        'known',
        'birth_date',
        'position',
        'birth_place',
        'first_nationality',
        'preferred_foot',
        'weight',
        'height',
        'jersey_num',
        'real_position',
        'real_position_side',
        'join_date',
        'country'
    ];


}
