<?php

namespace Dayscore\Http\Controllers;

use Dayscore\Team;
use Dayscore\Http\Requests;
use Illuminate\Http\Request;
use Kamaln7\Toastr\Facades\Toastr;
use Illuminate\Filesystem\Filesystem;

class TeamsController extends Controller
{
    public function __construct()
    {
        $this->middleware( 'auth' );
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = Team::latest()->paginate(20);
        return view('teams.index',compact('teams'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('teams.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Team::findOrNew($request->get('id'))->id){
            Toastr::error("El equipo ".$request->get('id')." ya existe");
            return redirect('teams');
        }
        $url = "http://futbol.dayscript.com/dayscore/images/teams/".$request->get('id').".png";
        $image = file_get_contents($url);
        $filename = public_path()."/images/teams/".$request->get('id').".png";
        $fs = new Filesystem();
        $fs->put($filename,$image);
//        Storage::put($filename, $image);

//        file_put_contents($filename,$image);


        Team::create($request->all());
        Toastr::success("Equipo creado correctamente!");
        return redirect('teams');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Team $team
     * @return \Illuminate\Http\Response
     */
    public function edit(Team $team)
    {
        return view('teams.edit',compact('team'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Team $team
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team)
    {
        $team->update($request->all());
        Toastr::success("Equipo actualizado correctamente!");
        return redirect('teams');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Team $team
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        $id = $team->id;
        $team->delete();
        return ['id' => $id];
    }
}
