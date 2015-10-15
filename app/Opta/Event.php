<?php

namespace Dayscore\Opta;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'opta_events';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'type',
        'game_id',
        'minute',
        'second',
        'time',
        'datetime',
        'period',
        'goal_type',
        'red_card_type',
        'player_id',
        'team_id',
        'sub_reason',
        'sub_on_player_id',
        'comment',
        'comment_type',
        'comment_player_ref2'
    ];

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function player()
    {
        return $this->belongsTo( 'Dayscore\Opta\Player' );
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo( 'Dayscore\Opta\Team' );
    }
}
