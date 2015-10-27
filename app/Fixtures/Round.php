<?php

namespace Dayscore\Fixtures;

use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fixture_rounds';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','fixture_id','order'];

    /**
     * Return all Matches objects associated with this Round
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function matches()
    {
        return $this->hasMany('Dayscore\Fixtures\Match')->orderBy('order', 'asc');
    }

    /**
     * Creates $number matches in current object
     *
     * @param $number
     */
    public function createMatches($number)
    {
        for($i=1;$i<=$number;$i++){
            $this->matches()->create(['order'=>$i]);
        }
    }
}
