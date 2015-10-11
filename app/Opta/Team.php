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

    public function image()
    {
        // 20x20 Image
        // http://images.akamai.opta.net/football/team/badges_20/XX.png
        // 150x150 image
        // http://images.akamai.opta.net/football/team/badges_150/XX.png
        return "http://images.akamai.opta.net/football/team/badges_150/".$this->id.".png";
    }
}
