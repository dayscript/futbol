<?php

namespace Dayscore\Http\Controllers;

use Dayscore\Team;
use Dayscore\Tournament;
use Illuminate\Http\Request;
use Dayscore\Http\Requests;
use Dayscore\Http\Controllers\Controller;
use Kamaln7\Toastr\Facades\Toastr;

class TournamentsController extends Controller
{
    public function __construct()
    {
        $this->middleware( 'auth',['except' => ['sync']] );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tournaments = Tournament::all();
        return view('tournaments.index',compact('tournaments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Tournament $tournament)
    {
        return view('tournaments.show',compact('tournament'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function sync($optaid, $optaseason)
    {
        $url = "http://futbol.dayscript.com/minamin/tournaments/tournamentopta/". $optaid."/".$optaseason;
        $json = json_decode(file_get_contents($url));
        if($json->status == "success"){
            $team = Tournament::firstOrCreate(["id"=>$json->data->id]);
            $team->update(["name"=>$json->data->name,"opta_id"=>$optaid,"opta_season"=>$optaseason]);
            Toastr::success("Se ha sincronizado el torneo correctamente!");
            return redirect('tournaments');
        } else {
            Toastr::error("Ha ocurrido un error al sincronizar el torneo.");
            return redirect('tournaments');
        }
    }
}
