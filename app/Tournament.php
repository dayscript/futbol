<?php

namespace Dayscore;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class Tournament extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','name','opta_id','opta_season'];

    /**
     * The attributes that should be treated as Carbon dates.
     *
     * @var array
     */
    protected $dates = ['created_at','updated_at'];

    /**
     * Format date attribute more human friendly
     *
     * @param $date
     * @return string
     */
    public function getCreatedAtAttribute( $date )
    {
        Carbon::setLocale('es');
        return Carbon::parse($date);
    }

    /**
     * Format date attribute more human friendly
     *
     * @param $date
     * @return string
     */
    public function getUpdatedAtAttribute( $date )
    {
        Carbon::setLocale('es');
        return Carbon::parse($date)->diffForHumans();
    }

    /**
     * Get the opta-games associated with this tournament
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function optagames()
    {
        return $this->hasMany( 'Dayscore\Opta\Game' )->orderBy('date','asc');
//        return $this->hasMany( 'Dayscore\Opta\Game' )->orderBy('date','desc');
    }

    /**
     * Get the opta-teams associated with this tournament
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function optateams()
    {
        return $this->belongsToMany('Dayscore\Opta\Team','opta_team_tournament','tournament_id','opta_team_id')->withTimestamps();
    }

    /**
     * Get the opta-players associated with this tournament
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function optaplayers()
    {
        return $this->belongsToMany('Dayscore\Opta\Player','opta_player_tournament','tournament_id','player_id')->withPivot('team_id', 'join_date','jersey_num','position','real_position')->withTimestamps();
    }

    public function updatewidget()
    {
        $tournament = $this;
        if($this->id=="150946"){ // Champions
            $dates = ["2015-12-08","2015-12-09"];
            $view = View::make('tournaments.widget',compact('tournament','dates'));
            $content = $view->render();
            Storage::disk('s3')->put('/resultswidget/150946_0.txt',$content);
            Storage::disk('s3')->setVisibility('/resultswidget/150946_0.txt', 'public');
            Storage::disk('s3')->put('/resultswidget/150946_7643.txt',$content);
            Storage::disk('s3')->setVisibility('/resultswidget/150946_7643.txt', 'public');
        } else if($this->id == "150945"){ //Sudamericana
            $dates = ["2015-12-02"];
            $view = View::make('tournaments.widget',compact('tournament','dates'));
            $content = $view->render();
            Storage::disk('s3')->put('/resultswidget/150945_0.txt',$content);
            Storage::disk('s3')->setVisibility('/resultswidget/150945_0.txt', 'public');
            Storage::disk('s3')->put('/resultswidget/150945_7678.txt',$content);
            Storage::disk('s3')->setVisibility('/resultswidget/150945_7678.txt', 'public');
        } else if($this->id == "150942"){ // Premier
            $dates = ["2015-11-28","2015-11-29"];
            $view = View::make('tournaments.widget',compact('tournament','dates'));
            $content = $view->render();
            Storage::disk('s3')->put('/resultswidget/150942_0.txt',$content);
            Storage::disk('s3')->setVisibility('/resultswidget/150942_0.txt', 'public');
            Storage::disk('s3')->put('/resultswidget/150942_7525.txt',$content);
            Storage::disk('s3')->setVisibility('/resultswidget/150942_7525.txt', 'public');
        } else if($this->id == "150944"){ //Serie A
            $dates = ["2015-12-04","2015-12-05","2015-12-06"];
            $view = View::make('tournaments.widget',compact('tournament','dates'));
            $content = $view->render();
            Storage::disk('s3')->put('/resultswidget/150944_0.txt',$content);
            Storage::disk('s3')->setVisibility('/resultswidget/150944_0.txt', 'public');
            Storage::disk('s3')->put('/resultswidget/150944_7606.txt',$content);
            Storage::disk('s3')->setVisibility('/resultswidget/150944_7606.txt', 'public');
        } else if($this->id == "150943"){ //Liga BBVA
            $dates = ["2015-12-05","2015-12-06","2015-12-07"];
            $view = View::make('tournaments.widget',compact('tournament','dates'));
            $content = $view->render();
            Storage::disk('s3')->put('/resultswidget/150943_0.txt',$content);
            Storage::disk('s3')->setVisibility('/resultswidget/150943_0.txt', 'public');
            Storage::disk('s3')->put('/resultswidget/150943_7565.txt',$content);
            Storage::disk('s3')->setVisibility('/resultswidget/150943_7565.txt', 'public');

        }
    }

}
