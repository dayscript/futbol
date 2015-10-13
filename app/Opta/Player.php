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
    public function image($teamid, $size="103x155")
    {
        $filename = "http://images.akamai.opta.net/football/player/".$teamid."_".$size."/".$this->id.".jpg";
        return $filename;
        $headers = @get_headers($filename);
        if(strpos($headers[0],'200')===false){
            return "/images/icons/player_".$size.".png";
        } else {
            return $filename;
        }

    }

}
