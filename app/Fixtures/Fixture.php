<?php

namespace Dayscore\Fixtures;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Kamaln7\Toastr\Facades\Toastr;

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
    protected $fillable = ['title', 'classicsRound', 'size', 'user_id'];

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
    public function getCreatedAtAttribute($date)
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
    public function getUpdatedAtAttribute($date)
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
        return $this->hasMany('Dayscore\Fixtures\Team')->orderBy('order', 'asc');
    }

    /**
     * Return all Ronds objects associated with this Fixture
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rounds()
    {
        return $this->hasMany('Dayscore\Fixtures\Round')->orderBy('order', 'asc');
    }

    public function setClassicsRound($include)
    {
        $round = Round::firstOrNew(["name" => "Fecha Clasicos ", "fixture_id" => $this->id]);
        if($include){
            $round->order = $this->size;
            $round->save();
            Toastr::info("Creada la fecha de clásicos");
        } else if($round->id){
            $round->delete();
            Toastr::info("Eliminada la fecha de clásicos");
        }
        return $this;
    }

    public function createRounds()
    {
        for ($i = 1; $i <= $this->size - 1; $i++) {
            if($i == $this->size/2 && $this->classicsRound)
                $this->rounds()->create(["name" => "Fecha ".$i.": Clasicos ", "order" => $this->size/2]);
            if($i >= $this->size/2 && $this->classicsRound)
                $this->rounds()->create(["name" => "Fecha " . ($i+1), "order" => $i+1]);
            else
                $this->rounds()->create(["name" => "Fecha " . $i, "order" => $i]);
        }
        return $this;
    }

    public function updateTeams()
    {
        $teams = $this->teams;
        $created = 0;
        $deleted = 0;
        for ($i = $teams->count() + 1; $i <= $this->size; $i++) {
            $this->teams()->create(["name" => "Equipo " . $i, "order" => $i]);
            $created++;
        }
        while ($this->size < $teams->count()) {
            $teams->pop()->delete();
            $deleted++;
        }
        if ($created) Toastr::info("Se agregaron {$created} equipos");
        if ($deleted) Toastr::info("Se eliminaron {$deleted} equipos");

    }

    /**
     * Create matches for every round in this fixture.
     */
    public function createMatches()
    {
        foreach ($this->rounds()->get() as $round) {
            $round->createMatches($this->size/2);
        }
    }

    /**
     * Updates teams in every match in this fixture.
     */
    public function updateMatches()
    {
        $max = $this->size-1;
        $team1 = 1;
        $team2 = $max;
        $roundNumber = 1;
        foreach ($this->rounds as $round) {
            if(strstr($round->name,"Clasicos")){
                $start1 = ($this->size/2)+1;
                $start2 = 1;
                foreach($round->matches as $match){
                    if($start1 == $this->size){
                        $match->home_id = Team::where('order',$start1)->where('fixture_id',$this->id)->first()->id;
                        $match->away_id = Team::where('order',$start2)->where('fixture_id',$this->id)->first()->id;
                    } else {
                        $match->away_id = Team::where('order',$start1)->where('fixture_id',$this->id)->first()->id;
                        $match->home_id = Team::where('order',$start2)->where('fixture_id',$this->id)->first()->id;
                    }
                    $match->save();
                    $start1 = ($start1<$this->size)?$start1+1:1;
                    $start2 = ($start2<$this->size/2)?$start2+1:1;
                }
            } else {
                foreach($round->matches as $match){
                    $match->home_id = Team::where('order',$team1)->where('fixture_id',$this->id)->first()->id;
                    if($match->order == 1){
                        if($roundNumber%2!=0)
                            $match->away_id = Team::where('order', $max+1)->where('fixture_id',$this->id)->first()->id;
                        else{
                            $match->away_id = $match->home_id;
                            $match->home_id = Team::where('order', $max+1)->where('fixture_id',$this->id)->first()->id;
                        }
                    } else {
                        $match->away_id = Team::where('order', $team2)->where('fixture_id',$this->id)->first()->id;
                        $team2 = ($team2>1)?$team2-1:$max;
                    }
                    if( ($match->away->order - $match->home->order == 11)
                            || (($match->home->order == $this->size) && ($match->away->order==(1+$this->size/2)))
                    ){
                        $temp = $match->home_id;
                        $match->home_id = $match->away_id;
                        $match->away_id = $temp;
                    }
                    $match->save();
                    $team1 = ($team1<$max)?$team1+1:1;
                }
                $roundNumber++;
            }
        }
    }

}
