<?php

namespace Dayscore\Opta;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'opta_teams';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','name'];

    public function image($size)
    {
        if($size && $size == 20){
            return "http://images.akamai.opta.net/football/team/badges_20/".$this->id.".png";
        } else {
            return "http://images.akamai.opta.net/football/team/badges_150/".$this->id.".png";
        }
    }
}
